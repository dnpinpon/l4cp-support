<?php namespace Gcphost\L4cpSupport\Helpers;

use Route;
use View;
use User;
use DB;
use Theme;
use TicketReplies;
use Ticket;
use TicketLog;

class Support {

	static public  $priorities=array(
			'0' => 'None',	
			'1' => 'Low',	
			'2' => 'Medium',	
			'3' => 'High',	
		);
	

	
	static public function Log($action, $tickets, $email=false){
	
		$log =  !$email ? new TicketLog() : new TicketLogEmail();
		$log->ticket_id = $tickets->id;
		$log->action =$action;
		$log->save();
	}

	static public function Trigger($action, $tickets, $email=false){
		self::Log($action, $tickets, $email);

		switch($action){
			case "open":
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

		self::ActionEmail($action, $tickets);
	}

	static private function ActionEmail($action, $tickets){

// if ticket department is set to auto respond otherwise do nada bia
		$autoreplies = TicketAutoreply::leftjoin('ticket_autoreply_actions', 'ticket_autoreply_actions.ticket_autoreply_id', '=', 'ticket_autoreply.id')
				->leftjoin('ticket_autoreply_deps', 'ticket_autoreply_deps.ticket_autoreply_id', '=', 'ticket_autoreply.id')
				->leftjoin('ticket_deps', 'ticket_deps.id', '=', 'ticket_autoreply_deps.ticket_deps_id')
				->where('ticket_autoreply_actions.ticket_actions_id', '=', $action)
				->groupBy(DB::raw('ticket_autoreply.id , ticket_autoreply.title , ticket_autoreply.content '));

		$user=User::find($tickets->user_id);
		$admin=User::find($tickets->admin_id);

		foreach($autoreplies as $id=>$ar){
      // still need to..

/*
need to add roll control to auto replie, admin or client

needs to know if ticket has a client, if no client needs to know if ticket has email and name, send to that - on any event

*/
	  // if trigger=reply then tickets=array(tickets, reply)
		if($action == "reply"){

		} else {

		}

			$replyto=($ar->email && $ar->pop_host) ? array('name' =>$ar->email, 'email'=>$ar->email) : false;
			self::SendActionEmail($to, $ar->title, $ar->content, $replyto, $action, $tickets, $user, $admin);

		}
	}

	static private function SendActionEmail($to, $subject, $body, $replyto, $action, $tickets){
		$body='From:'. Setting::get('support.from_name') . ' ('. Setting::get('support.from_email') .')<br/><br/>'.$body;

		if(!$replyto) $replyto=array('name' => Setting::get('support.reply_to_name'), 'email' => Setting::get('support.reply_to_email'));
		

		$send=Mail::send('l4cp-support::emails/default', array('subject' => $subject, 'body'=>$body, 'to' => $to, 'action' => $action, 'tickets' => $tickets), function($message)
		{
			$message->to($to->email)->subject($subject);
			$message->replyTo($replyto['name'], $replyto['email']);
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
		$replies = TicketReplies::leftjoin('users', 'users.id', '=', 'ticket_replies.admin_id')
                        ->select(array('ticket_replies.id as id', 'users.displayname as displayname','users.id as userid', 'users.email as useremail', 'ticket_replies.content', 'ticket_replies.updated_at'))->orderBy('ticket_replies.id', 'desc')->where('ticket_replies.ticket_id','=',$tickets->id)->get();
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


	static public function getStatusByName($status){
		return DB::table('ticket_statuses')->where('title', '=', str_replace('-', ' ',$status))->first();
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

	static public function getClients(){
		return User::leftjoin('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
                    ->leftjoin('roles', 'roles.id', '=', 'assigned_roles.role_id')
                    ->select(DB::raw('users.displayname,users.id'))->where('roles.name', '!=', 'admin')->groupBy(DB::raw('users.displayname,users.id'))->lists('displayname','id');

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