<?php namespace Gcphost\L4cpSupport\Helpers;

use TicketStatuses, Route,View,User,DB,Theme,TicketReplies,Ticket,TicketLog,TicketAutoreply,Setting,Mail,Role,TicketDeps,TicketSpam,TicketBreaklines,Lang,TicketEscalations,DateTime,Cron,CronWrapper;

class Support {

	static private $to;
	static private $subject;
	static private $body;
	static private $replyto;
	static private $action;
	static private $tickets;
	static private $user;
	static private $admin;
	static private $reply;
	static private $flagged;

	static public $priorities=array(
			'0' => 'None',	
			'1' => 'Low',	
			'2' => 'Medium',	
			'3' => 'High',	
	);
	
	static public function Cron(){	
		Cron::add('SupportCron', '*/5 * * * *', function() {
			Support::ProcessImports();
			Support::ProcessEscalations();
			Support::ProcessAutoClose();
			return true;
		}, true);
	}

	static public function Log($action, $tickets, $email=false){
		$log =  !$email ? new TicketLog() : new TicketLogEmail();
		$log->ticket_id = $tickets->id;
		$log->action =$action;
		$log->save();
	}

	static public function Trigger($action, $tickets, $reply=false,$email=false){
		self::Log($action, $tickets, $email);
		self::ActionEmail($action, $tickets, $reply);

		switch($action){
			case "create":
				return self::ActionCreate($tickets);
			break;
			case "reply":
				return self::ActionReply($tickets);
			break;
			case "edit":
				return self::ActionEdit($tickets);
			break;

			case "delete":
				return self::ActionDelete($tickets);
			break;
		}
	}

	static private function ActionEmail($action, $tickets, $reply=false){
		self::$tickets=$tickets;
		self::$action=$action;
		self::$reply=$reply;
		$act=self::getActionByName($action);
		if(!$act) return false;

		$autoreplies = 
				TicketAutoreply::join('ticket_autoreply_actions', 'ticket_autoreply_actions.ticket_autoreply_id', '=', 'ticket_autoreply.id')
				->join('ticket_autoreply_deps', 'ticket_autoreply_deps.ticket_autoreply_id', '=', 'ticket_autoreply.id')
				->join('ticket_autoreply_roles', 'ticket_autoreply_roles.ticket_autoreply_id', '=', 'ticket_autoreply.id')
				->join('ticket_deps', 'ticket_autoreply_deps.ticket_deps_id', '=', 'ticket_deps.id')
				->join('ticket_deps_flags', 'ticket_deps_flags.ticket_deps_id', '=', 'ticket_deps.id')
				->where('ticket_autoreply_actions.ticket_actions_id', '=', $act->id)
				->where('ticket_deps.auto_respond', '=', '1')
				->select(DB::raw('ticket_autoreply.id, ticket_autoreply.title, ticket_autoreply.content, ticket_deps.email, ticket_deps.pop_host, ticket_deps.pop_port, ticket_deps.pop_user,ticket_deps.pop_pass,Group_Concat(Distinct ticket_deps_flags.user_id) as flags, Group_Concat(Distinct ticket_autoreply_roles.role_id) as roles'))
				->groupBy(DB::raw('ticket_autoreply.id, ticket_autoreply.title, ticket_autoreply.content,ticket_deps.email, ticket_deps.pop_host, ticket_deps.pop_port, ticket_deps.pop_user, ticket_deps.pop_pass'))->get();
		
		foreach($autoreplies as $ar){
			$flags=array_merge(self::$tickets->currentFlags(),explode(',', $ar->flags));
			$roles=explode(',', $ar->roles);
			self::$flagged=false;
			$send_client=false;
			if(self::$tickets->user_id){
				self::$user=User::find(self::$tickets->user_id);
				self::$to=array('email'=>self::$user->email, 'name'=>self::$user->displayname);
				$send_client=array_intersect($roles, self::$user->currentRoleIds()) ? true : false;
				if($existing=array_search(self::$tickets->user_id, $flags)) unset($flags[$existing]);
			} elseif(self::$tickets->email){	
				self::$to=array('email'=>self::$tickets->email, 'name'=>self::$tickets->name);
			} else continue;

			self::$subject=$ar->title;
			self::$body=$ar->content;
			self::$replyto=$ar->pop_host ? array('name' =>$ar->email, 'email'=>$ar->email) : array('name' => Setting::get('support.reply_to_name'), 'email' => Setting::get('support.reply_to_email'));

			if($send_client) self::SendActionEmail();

			self::$flagged=true;
			foreach($flags as $user_id){
				if(self::$user=User::find($user_id)){
					self::$to=array('email'=>self::$user->email, 'name'=>self::$user->displayname);
					array_intersect($roles, self::$user->currentRoleIds()) ? self::SendActionEmail() : false;
				}
			}
		}
	}

	static private function SendActionEmail(){
		$send=Mail::send('l4cp-support::emails/default', array('flagged' => self::$flagged,'subject' => self::$subject, 'body'=>self::$body, 'to' => self::$to, 'action' => self::$action, 'tickets' => self::$tickets, 'reply' => self::$reply), function($message)
		{
			$message->to(self::$to['email'], self::$to['name'])->subject('['.Lang::get('l4cp-support::core.ticket_id').': '.self::$tickets->id.'] '.self::$subject);
			if(self::$replyto['email']) $message->replyTo(self::$replyto['email'], self::$replyto['name']);
		});
		return $send ? true : false;
	}

	static private function ActionCreate($tickets){

	}

	static private function ActionReply($tickets){

	}

	static private function ActionEdit($tickets){

	}
	
	static private function ActionDelete($tickets){

	}

	static public function getTicketThreadItems($tickets, $update=false){
		if($update) $tickets=Ticket::find($tickets->id);
		$button=self::getDefaultButtonOption();
		$user=User::find($tickets->user_id);
		$admin=User::find($tickets->admin_id);
		$replies = TicketReplies::leftjoin('users', 'users.id', '=', 'ticket_replies.user_id')
                        ->select(array('ticket_replies.id as id', 'users.displayname as displayname','users.id as userid', 'users.email as useremail', 'ticket_replies.content', 'ticket_replies.updated_at', 'ticket_replies.name', 'ticket_replies.email'))->orderBy('ticket_replies.id', 'desc')->where('ticket_replies.ticket_id','=',$tickets->id)->get();
		return array($user, $replies, $tickets, $admin, $button);

	}

	static public function getTicketThread($tickets){
		$thread=self::getTicketThreadItems($tickets, true);
		$user=$thread[0];
		$replies=$thread[1];
		$tickets=$thread[2];
		$admin=$thread[3];
		$button=$thread[4];

		return array('type'=>'html', 'args'=>Theme::make('l4cp-support::thread/content', compact('tickets', 'user', 'replies', 'admin', 'button'))->render());
	}

	static public function getDefaultReplyOption(){
		return DB::table('ticket_statuses')->select('id')->where('default_category', '=', '1')->first();
	}

	static public function getDefaultButtonOption(){
		return DB::table('ticket_statuses')->select('id', 'title')->where('default_button', '=', '1')->first();
	}

	static public function getRoles(){
		return Role::all()->lists('name', 'id');
	}

	static public function getStatusByName($status){
		return DB::table('ticket_statuses')->where('title', '=', str_replace('-', ' ',$status))->first();
	}

	static public function getActionByName($action){
		return DB::table('ticket_actions')->where('name', '=', $action)->first();
	}

 	static public  function getActions(){
		return DB::table('ticket_actions')->orderBy('id', 'asc')->lists('name','id');
	}

	static public function getDeps(){
        return DB::table('ticket_deps')->orderBy('id', 'asc')->lists('name','id');
	}

	static public function getStatuses(){
        return DB::table('ticket_statuses')->orderBy('sort', 'asc')->orderBy('id', 'asc')->lists('title','id');
	}

	static public function getStatusesCount(){
        return DB::table('ticket_statuses')->leftjoin('ticket', 'ticket.status', '=', 'ticket_statuses.id')->select(DB::raw('ticket_statuses.id, ticket_statuses.title, count(ticket.id) as total'))->orderBy('sort', 'asc')->orderBy('id', 'asc')->groupBy(DB::raw('ticket_statuses.id, ticket_statuses.title'))->get();
	}
	static public function getAdmins(){
		return User::leftjoin('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                    ->leftjoin('roles', 'roles.id', '=', 'assigned_roles.role_id')
                    ->select(DB::raw('users.displayname,users.id'))->where('roles.name', '=', 'admin')->groupBy(DB::raw('users.displayname,users.id'))->lists('displayname','id');
	}


	static public function ProcessImport($department, $import){
		foreach($import as $data){
			if($ticket=self::ProcessImportSubject($data[3])){
				self::ProcessImportReply($ticket, $data);
			} else self::ProcessImportSave($data);
		}
	}

	static public function ProcessImports(){
		$deps=TicketDeps::get();
		foreach($deps as $id => $d){
			if($d->pop_host && $d->pop_port && $d->pop_user && $d->pop_pass) self::ProcessImport($d->id, self::Import($d->pop_host, $d->pop_port, $d->pop_user, $d->pop_pass));
		}
	}

	static public function ProcessUserEmail($email){
		$user=User::select('id')->where('email', '=', $email)->first();
		return isset($user->id) ? $user->id : false;
	}

	static public function ProcessImportSubject($subject){
		preg_match('/\['.Lang::get('l4cp-support::core.ticket_id').': (.*?)\]/', $subject, $match);
		if(isset($match[1]) && is_numeric($match[1])) return $match[1];
		return false;
	}

	static public function ProcessSave($tickets, $data){
		if($user_id=self::ProcessUserEmail($data[2])){
			$tickets->user_id = $user_id;
		} else {
			$tickets->name = $data[1];
			$tickets->email = $data[2];
		}

		$tickets->department_id = Setting::get('support.default_department');
		$tickets->status = Setting::get('support.default_status');

		$tickets->title = $data[3];
		$tickets->message = $data[4];

		// TO-DO: one day ad file attachement support, loop array, save files, store in related table
		//$tickets->attachment = $data[5];

		return $tickets->save() ? true : false;
	}

	static public function ProcessImportReply($ticket_id, $data){
		$tickets=Ticket::find($ticket_id);
		if($tickets && self::ProcessImportReplySave($tickets, $data)){
			Support::Trigger('reply', $tickets, $data[4], true);
			return true;
		} else return false;
	}

	static public function ProcessImportSave($data){
		$tickets=new Ticket;
		if(self::ProcessSave($tickets, $data)){
			Support::Trigger('open', $tickets, false, true);
			return true;
		} else return false;           		
	}

	static public function ProcessImportReplySave($tickets, $data){
		$reply=new TicketReplies;
		$reply->content=$data[4];

		if($user_id=self::ProcessUserEmail($data[2])){
			$reply->user_id = $user_id;
		} else {
			$reply->name = $data[1];
			$reply->email = $data[2];
		}		

		return $tickets->replies()->save($reply) ? true : false;
	}

	static public function ProcessMessageBreaks($message){
		$breaks=TicketBreaklines::get();
		foreach($breaks as $line){
			preg_match("/".$line->breakline."/s", $message, $match);
			if(is_array($match) && isset($match[0])) return $match[0];
		}
		return $message;
	}
	
	static public function ProcessSpam($sender, $subject, $body){
		$spam=TicketSpam::get();
		$check=array();
	
		foreach($spam as $id => $s) $check[$s->type][]=$s->content;

		foreach($check as $type=>$content){
			switch($type){
				case 'sender':
					$data=$sender;
				break;
				case 'subject':
					$data=$subject;
				break;
				case 'body':
					$data=$body;
				break;
			}
			foreach($content as $c){
				if(preg_match('/'.$c.'/s', $data)) return true;
			}
		}
		return false;
	}

	static public function ProcessMessage($message){
		return preg_replace(array("/=(\r?)\n/","/=20(\r?)\n/"), '', self::ProcessMessageBreaks($message));
	}

	static public function Import($server, $port, $user, $pass){
		$parse=array();
		$inbox = imap_open('{'.$server.':'.$port.'/imap/ssl/novalidate-cert}INBOX', $user, $pass) or die('Unable to access mail: ' . imap_last_error());
		$emails = imap_search($inbox,'UNSEEN');

		if($emails) {
			rsort($emails);
			foreach($emails as $email_number) {
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$structure = imap_fetchstructure($inbox,$email_number);
				$part=$structure->parts[0];
				$message=imap_fetchbody($inbox,$email_number,1.1) ? : imap_fetchbody($inbox,$email_number,1);
				
				if($part->encoding == 3) {
					$message = imap_base64($message);
				} else if($part->encoding == 1) {
					$message = imap_8bit($message);
				} else $message = imap_qprint($message);

				$message=self::ProcessMessage($message);
				$header = imap_header($inbox, $email_number);

				$header=$header->from;
				$name=$header[0]->personal;
				$from=$header[0]->mailbox."@".$header[0]->host;
				$subject=$overview[0]->subject;
				$date=$overview[0]->date;

				if(self::ProcessSpam($from, $subject, $message)) continue;

				$attachments = array();
				if(isset($structure->parts) && count($structure->parts)) {
					for($i = 1; $i < count($structure->parts); $i++) {
						$attachments[$i] = array(
						'is_attachment' => false,
						'filename' => '',
						'name' => '',
						'attachment' => '');

						if($structure->parts[$i]->ifdparameters) {
							foreach($structure->parts[$i]->dparameters as $object) {
								if(strtolower($object->attribute) == 'filename') {
									$attachments[$i]['is_attachment'] = true;
									$attachments[$i]['filename'] = $object->value;
								}
							}
						}

						if($structure->parts[$i]->ifparameters) {
							foreach($structure->parts[$i]->parameters as $object) {
								if(strtolower($object->attribute) == 'name') {
									$attachments[$i]['is_attachment'] = true;
									$attachments[$i]['name'] = $object->value;
								}
							}
						}

						if($attachments[$i]['is_attachment']) {
							$attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
							if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
								$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
							}elseif($structure->parts[$i]->encoding == 4) $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
						}             
					} 
				} 
				$parse[]=array($date, $name, $from, $subject, $message, $attachments);
			}
		}
		imap_close($inbox);

		return $parse;
	}


	static public function ProcessAutoClose(){
		$close=TicketStatuses::where('close_status', '=', '1')->first();
		if($close->id) $tickets=Ticket::where('ticket.updated_at','<',DB::raw('NOW() - INTERVAL '.Setting::get('support.auto_close_delay').' DAY'))
			->leftjoin('ticket_statuses', 'ticket_statuses.id', '=', 'ticket.status')
			->where('ticket_statuses.auto_close', '=','1')
			->groupBy('ticket.id')->update(array('ticket.status' => $close->id));

	}


	static public function ProcessEscalations(){
		$escalations= TicketEscalations::leftjoin('ticket_escalations_deps', 'ticket_escalations_deps.ticket_escalations_id', '=', 'ticket_escalations.id')
                    ->leftjoin('ticket_escalations_flags', 'ticket_escalations_flags.ticket_escalations_id', '=', 'ticket_escalations.id')
                    ->leftjoin('ticket_escalations_sta', 'ticket_escalations_sta.ticket_escalations_id', '=', 'ticket_escalations.id')
                    ->select(DB::raw('ticket_escalations.*, Group_Concat(Distinct ticket_escalations_deps.ticket_deps_id) as departments, Group_Concat(Distinct ticket_escalations_flags.user_id)	as flags, Group_Concat(Distinct ticket_escalations_sta.ticket_statuses_id) as statuses'))->groupBy(DB::raw('ticket_escalations.id'))->get();
		foreach($escalations as $esc) self::ProcessEscalationTickets($esc);
	}

	static public function ProcessEscalationTickets($escalation){
		$results=Ticket::select('ticket.id')->where('ticket.updated_at','<',DB::raw('NOW() - INTERVAL '.$escalation->delay.' MINUTE'));
		if($escalation->departments) $results->whereIn('ticket.department_id',explode(',',$escalation->departments));
		if($escalation->status) $results->whereIn('ticket.status',explode(',',$escalation->status));
		if($escalation->priority) $results->whereIn('ticket.priority',json_decode($escalation->priority));
		
		foreach($results->get() as $ticket){
			$tickets=Ticket::find($ticket->id);
		
			if($escalation->new_priority) $tickets->priority = $escalation->new_priority;
			if($escalation->new_status) $tickets->status = $escalation->new_status;
			if($escalation->new_department) $tickets->department_id = $escalation->new_department;
			if($escalation->flags) $tickets->saveFlags(explode(',',$escalation->flags));
			$tickets->updated_at=new DateTime();
			$tickets->save();

			Support::Trigger('edit', $tickets);
		}
	}

	static public function AdminGroup(){

		Route::controller('support/autoreplies/{reply}', 'SupportAutoreplyController');
		Route::controller('support/autoreplies', 'SupportAutoreplyController');

		Route::controller('support/statuses/{status}', 'SupportStatusesController');
		Route::controller('support/statuses', 'SupportStatusesController');

		Route::controller('support/escalations/{escalation}', 'SupportEscalationsController');
		Route::controller('support/escalations', 'SupportEscalationsController');

		Route::controller('support/spam/{spam}', 'SupportSpamfilterController');
		Route::controller('support/spam', 'SupportSpamfilterController');

		Route::controller('support/departments/{department}', 'SupportDepartmentController');
		Route::controller('support/departments', 'SupportDepartmentController');

		Route::controller('support/{tickets}/thread', 'SupportThreadController');

		Route::get('support/data/{postSlug}', 'SupportController@getData');
		Route::controller('support/{tickets}', 'SupportController');
		Route::get('support/{postSlug}', 'SupportController@getView');
		Route::post('support/{postSlug}', 'SupportController@postView');
		Route::controller('support', 'SupportController');

	}
}