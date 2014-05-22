<?php
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;


class SupportThreadController extends BaseController {


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


 		/**
     * Show a list of all the blog posts.
     *
     * @return View
     */
    public function getIndex($tickets)
    {

		$thread=Support::getTicketThreadItems($tickets);
		$user=$thread[0];
		$replies=$thread[1];
		$admin=$thread[3];
		$button=$thread[4];


		return Theme::make('l4cp-support::thread/index', compact('tickets', 'user', 'replies', 'admin', 'button'));

    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$replyoption=Support::getDefaultReplyOption();
		$button=Support::getDefaultButtonOption();
		$title = Lang::get('l4cp-support::core.create_a_new_reply');
		return Theme::make('l4cp-support::thread/create_edit', compact('title', 'replyoption', 'button'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postCreate($tickets)
	{
        $rules = array(
            'content'   => 'required|min:3',
        );

		$validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {

			$tickets->status=Input::get('status');
			$tickets->save();

			$reply=new TicketReplies;
			$reply->content=Input::get('content');
			$reply->user_id=Confide::user()->id;
			

            if($tickets->replies()->save($reply))
            {			
				
				Support::Trigger('reply', $tickets, $reply);

                return Api::to(array('success', Lang::get('l4cp-support::messages.create.success'))) ? : Redirect::to('admin/support/' . $tickets->id . '/thread/create')->with('success', Lang::get('l4cp-support::messages.create.success'));
            } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/' . $tickets->id . '/thread/create')->with('error', Lang::get('l4cp-support::messages.create.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/' . $tickets->id . '/thread/create')->withInput()->withErrors($validator);
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param $post
     * @return Response
     */
	public function getEdit($tickets)
	{

		$replyoption=Support::getDefaultReplyOption();
		$button=Support::getDefaultButtonOption();

       $title = Lang::get('l4cp-support::core.thread_update');
       return Theme::make('l4cp-support::thread/create_edit', compact('tickets', 'title', 'replyoption', 'button'));
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
			$tickets->admin_id = Confide::user()->id;
			$tickets->department_id = Input::get('department_id');

			$tickets->name = Input::get('name');
			$tickets->email = Input::get('email');

			$tickets->title = Input::get('title');
			$tickets->attachment = Input::get('attachment');
			$tickets->message = Input::get('content');
			$tickets->priority = Input::get('priority');
			$tickets->status = Input::get('status');

			$tickets->saveFlags(Input::get('flags'));

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
		$id = $tickets->id;
		if(!$tickets->delete()) return Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));
		$tickets=TicketDeps::find($id);
        return empty($tickets) ? Api::json(array('result'=>'success')) : Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));        
    }



}