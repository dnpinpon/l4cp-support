<?php
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;


class SupportController extends BaseController {


    /**
     * Post Model
     * @var Post
     */
    protected $tickets;

    /**
     * Inject the models.
     * @param Post $post
     */
    public function __construct(Ticket $tickets)
    {
        parent::__construct();
        $this->tickets = $tickets;
    }

	public function getInstall(){
		if(!Setting::has('support.from_name')) Setting::set('support.from_name', '');
		if(!Setting::has('support.from_email')) Setting::set('support.from_email', '');
		if(!Setting::has('support.reply_to_name')) Setting::set('support.reply_to_name', '');
		if(!Setting::has('support.reply_to_email')) Setting::set('support.reply_to_email', '');
		if(!Setting::has('support.default_department')) Setting::set('support.default_department', '1');
		if(!Setting::has('support.default_status')) Setting::set('support.default_status', '1');
		if(!Setting::has('support.auto_close_delay')) Setting::set('support.auto_close_delay', '1');

		Setting::save();

		echo "Support package installed.";
	}

 	/**
     * Show a list of all the blog posts.
     *
     * @return View
     */
    public function getThread($tickets)
    {
		$user=User::find($tickets->user_id);
		return Theme::make('l4cp-support::thread/index', compact('tickets', 'user'));

    }

    public function getThreadReply($tickets)
    {
		exit;
		$user=User::find($tickets->user_id);
		return Theme::make('l4cp-support::thread/index', compact('tickets', 'user'));

    }

	/**
     * Show a list of all the blog posts.
     *
     * @return View
     */
    public function getView($slug)
    {

		/* hacks to ensure they get to the right place */
		if($slug == "test") return Support::ProcessAutoClose();
		if($slug == "test2") return Support::ProcessEscalations();
		if($slug == "cron") return $this->getCron();
		
		if($slug == "data") return $this->getData();
		if($slug == "create") return $this->getCreate();
		if($slug == "install") return $this->getInstall();

		$department=Support::getStatusByName($slug);


		$title = Lang::get('l4cp-support::core.support');
		return Theme::make('l4cp-support::tickets/index', compact('title', 'slug', 'department'));

    }

    public function postView($slug)
    {
		if($slug == "create") return $this->postCreate();

    }


 	/**
     * Show a list of all the blog posts.
     *
     * @return View
     */
    public function getIndex()
    {
       
		$title = Lang::get('l4cp-support::core.support');
		return Theme::make('l4cp-support::tickets/index', compact('title'));

    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$title = Lang::get('l4cp-support::core.create_a_new_ticket');
		return Theme::make('l4cp-support::tickets/create_edit', compact('title'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postCreate()
	{
        $rules = array(
            'title'   => 'required|min:3',
        );

		$validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes() && (Input::get('user_id') || Input::get('email')))
        {

			if(Input::get('user_id')) $this->tickets->user_id = Input::get('user_id');
			$this->tickets->admin_id = Confide::user()->id;
			$this->tickets->department_id = Input::get('department_id');

			$this->tickets->name = Input::get('name');
			$this->tickets->email = Input::get('email');

			$this->tickets->title = Input::get('title');
			$this->tickets->attachment = Input::get('attachment');
			$this->tickets->message = Input::get('content');
			$this->tickets->priority = Input::get('priority');
			$this->tickets->status = Input::get('status');

			$this->tickets->saveFlags(explode(',',Input::get('flags')));


            if($this->tickets->save())
            {			
				$not = new TicketNotes(array('id'=>'0','content'=>Input::get('notes'), 'admin_id' =>Confide::user()->id));
				$this->tickets->notes()->save($not);
				
				Support::Trigger('open', $this->tickets);

                return Api::to(array('success', Lang::get('l4cp-support::messages.create.success'))) ? : Redirect::to('admin/support/' . $this->tickets->id . '/edit')->with('success', Lang::get('l4cp-support::messages.create.success'));
            } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/create')->with('error', Lang::get('l4cp-support::messages.create.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/create')->withInput()->withErrors($validator);
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param $post
     * @return Response
     */
	public function getEdit($tickets)
	{

       $title = Lang::get('l4cp-support::core.tickets_update');
       return Theme::make('l4cp-support::tickets/create_edit', compact('tickets', 'title'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param $post
     * @return Response
     */
	public function putEdit($tickets)
	{

        $rules = array(
            'title'   => 'required|min:3',
        );

        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
			$tickets->user_id = Input::get('user_id');
			//$tickets->admin_id = Confide::user()->id;
			$tickets->department_id = Input::get('department_id');

			$tickets->name = Input::get('name');
			$tickets->email = Input::get('email');

			$tickets->title = Input::get('title');
			$tickets->attachment = Input::get('attachment');
			$tickets->message = Input::get('content');
			$tickets->priority = Input::get('priority');
			$tickets->status = Input::get('status');

			$tickets->saveFlags(explode(',',Input::get('flags')));

			foreach(Input::get('notes') as $id=>$note){
				$not = TicketNotes::find($id);
				if(!empty($not)){
					if(trim($note) != ''){
						$not->fill(array('id'=>$id,'content'=>$note))->push();
					} else $not->delete();
				} else {
					$not = new TicketNotes(array('id'=>$id,'content'=>$note, 'admin_id' =>Confide::user()->id));
					$tickets->notes()->save($not);
				}
			}

			Support::Trigger('edit', $tickets);

            return $tickets->save() ? Api::to(array('success', Lang::get('l4cp-support::messages.update.success'))) ? : Redirect::to('admin/support/' . $tickets->id . '/edit')->with('success', Lang::get('l4cp-support::messages.update.success')) : Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/' . $tickets->id . '/edit')->with('error', Lang::get('l4cp-support::messages.update.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/' . $tickets->id . '/edit')->withInput()->withErrors($validator);
	}


    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function deleteIndex($tickets)
    {
		Support::Trigger('delete', $tickets);
		$id = $tickets->id;
		if(!$tickets->delete()) return Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));
		$tickets=TicketDeps::find($id);
        return empty($tickets) ? Api::json(array('result'=>'success')) : Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));        
    }

	public function getStatus($tickets, $status){
		$tickets->status=$status;
		return $tickets->save() ? Api::json(array('result'=>'success')) : Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));        
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
	 public function getActivity($tickets){
        if ( $tickets->id )
        {
			$list = TicketLog::whereRaw('ticket_id = ?', array($tickets->id))->select(array('id','action', 'created_at'));

			if(Api::Enabled()){
				$u=$list->get();
				return Api::make($u->toArray());
			} else return Datatables::of($list)
				 ->edit_column('created_at','{{{ Carbon::parse($created_at)->diffForHumans() }}}')
				->make();
		}
	 }



    /**
     * get user notes
     *
     * @return Response
     */
    public function getNotes($tickets)
    {

        if ( $tickets->id )
        {
			$list = TicketNotes::leftjoin('users', 'users.id', '=', 'ticket_notes.admin_id')
					->select(array('ticket_notes.id', 'ticket_notes.content', 'ticket_notes.created_at', 'ticket_notes.updated_at', 'users.displayname'))->where('ticket_notes.ticket_id','=',$tickets->id);
			if(Api::Enabled()){
				$u=$list->get();
				return Api::make($u->toArray());
			} else return Datatables::of($list)
				 ->edit_column('content','<textarea name="notes[{{{$id}}}]" class="form-control" style="width: 100%">{{{ $content }}}</textarea>')
				 ->edit_column('created_at','{{{ Carbon::parse($created_at)->diffForHumans() }}}')
				 ->edit_column('updated_at','{{{ Carbon::parse($updated_at)->diffForHumans() }}}')
				->make();
		}
	}


    /**
     * Show a list of all the blog posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData($slug=false)
    {
		if($slug){
			$department=Support::getStatusByName($slug);
			if($department->default_flag){
				$list=Ticket::select(array('ticket.id','priority','users.displayname','ticket.title', 'ticket_deps.name', 'ticket_statuses.title as status', 'ticket.updated_at', 'ticket_statuses.color as color'))
						->leftjoin('ticket_flags', 'ticket_flags.ticket_id', '=', 'ticket.id')->where('ticket_flags.user_id', '=', Confide::user()->id)
						->leftjoin('ticket_statuses', 'ticket_statuses.id', '=', 'ticket.status');
;
			} else $list=Ticket::select(array('ticket.id','priority','users.displayname','ticket.title', 'ticket_deps.name', 'ticket.updated_at'))->where('status', '=', $department->id);
		} else $list = Ticket::select(array('ticket.id','priority','users.displayname','ticket.title', 'ticket_deps.name', 'ticket_statuses.title as status', 'ticket.updated_at', 'ticket_statuses.color as color'))
						->leftjoin('ticket_statuses', 'ticket_statuses.id', '=', 'ticket.status')->where('ticket_statuses.show_active','=','1');
						//->leftjoin('ticket_flags', 'ticket_flags.ticket_id', '=', 'ticket.id')->where(DB::raw('ticket_flags.user_id = '. Confide::user()->id. ' OR ticket_flags.user_id = ""'));

		

		$list->leftjoin('users', 'users.id', '=', 'ticket.user_id')->leftjoin('ticket_deps', 'ticket_deps.id', '=', 'ticket.department_id');


		if(Api::Enabled()){
			$u=$list->get();
			return Api::make($u->toArray());
		} else return Datatables::of($list)
		->remove_column('color')
		->edit_column('displayname', '<a href="{{{ URL::to(\'admin/support/\' . $id . \'/thread\' ) }}}">{{{ $displayname }}}')
		->edit_column('updated_at', '{{{ Carbon::parse($updated_at)->diffForHumans() }}}')
		->edit_column('priority', '{{ $priority > 0 ? "<span class=\"fa fa-flag". ($priority > 1 ? ($priority  == 3 ? "-checkered" : "-o") : null) ." \"></span>" : null}}')
		->edit_column('title', '<a href="{{{ URL::to(\'admin/support/\' . $id . \'/thread\' ) }}}">{{{ Filter::filter(Str::limit(strip_tags($title), 42, "..."), "*") }}}</a>')
		->edit_column('status', '@if(isset($status))@if(isset($color))<span style="color: {{{ $color }}}">{{{ $status }}}</span>@else{{{ $status }}}@endif@endif')

        ->add_column('actions', '<div class="btn-group"><a href="{{{ URL::to(\'admin/support/\' . $id . \'/thread\' ) }}}" class="link-through btn btn-info btn-sm" >{{{ Lang::get(\'button.view\') }}}</a><a href="{{{ URL::to(\'admin/support/\' . $id . \'/edit\' ) }}}" class="btn btn-primary btn-sm modalfy" >{{{ Lang::get(\'button.edit\') }}}</a>
                <a data-method="delete" data-row="{{{  $id }}}" data-table="tickets"  href="{{{ URL::to(\'admin/support/\' . $id . \'\' ) }}}" class="confirm-ajax-update btn btn-sm btn-danger">{{{ Lang::get(\'button.delete\') }}}</a></div>
            ')

        ->make();
    }

}