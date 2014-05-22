<?php
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;


class SupportAutoreplyController extends BaseController {


    /**
     * Post Model
     * @var Post
     */
    protected $autoreply;

    /**
     * Inject the models.
     * @param Post $post
     */
    public function __construct(TicketAutoreply $autoreply)
    {
        parent::__construct();
        $this->autoreply = $autoreply;
    }



	
	/**
     * Show a list of all the blog posts.
     *
     * @return View
     */
    public function getIndex()
    {
       
		$deps=Support::getDeps();
		$actions=Support::getActions();
		$title = Lang::get('l4cp-support::core.auto_replies');
		return Theme::make('l4cp-support::autoreplys/index', compact('title', 'deps', 'actions'));

    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{

		$roles=Support::getRoles();;
		$deps=Support::getDeps();;
		$actions=Support::getActions();
		$title = Lang::get('l4cp-support::core.create_a_new_autoreply');
		return Theme::make('l4cp-support::autoreplys/create_edit', compact('title', 'deps', 'actions', 'roles'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postCreate()
	{
        $rules = array(
            'title'   => 'required',
            'content'   => 'required',
            'roles'   => 'required',
            'actions'   => 'required',
            'departments'   => 'required',
        );

		$validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {

            // Update the blog post data
            $this->autoreply->title = Input::get('title');
            $this->autoreply->content = Input::get('content');
			$this->autoreply->saveRoles(Input::get( 'roles' ));
			$this->autoreply->saveDeps(Input::get( 'departments' ));
			$this->autoreply->saveActions(Input::get( 'actions' ));

            if($this->autoreply->save())
            {
                return Api::to(array('success', Lang::get('l4cp-support::messages.create.success'))) ? : Redirect::to('admin/support/autoreplies/' . $this->autoreply->id . '/edit')->with('success', Lang::get('l4cp-support::messages.create.success'));
            } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/autoreplies/create')->with('error', Lang::get('l4cp-support::messages.create.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/autoreplies/create')->withInput()->withErrors($validator);
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param $post
     * @return Response
     */
	public function getEdit($autoreply)
	{

		$roles=Support::getRoles();;
		$deps=Support::getDeps();;
		$actions=Support::getActions();
        $title = Lang::get('l4cp-support::core.autoreply_update');
        return Theme::make('l4cp-support::autoreplys/create_edit', compact('autoreply', 'title', 'deps', 'actions', 'roles'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param $post
     * @return Response
     */
	public function putEdit($autoreply)
	{

        $rules = array(
            'title'   => 'required',
            'content'   => 'required',
            'roles'   => 'required',
            'actions'   => 'required',
            'departments'   => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
            $autoreply->title = Input::get('title');
            $autoreply->content = Input::get('content');
			$autoreply->saveDeps(Input::get( 'departments' ));
			$autoreply->saveRoles(Input::get( 'roles' ));
			$autoreply->saveActions(Input::get( 'actions' ));

            return $autoreply->save() ? Api::to(array('success', Lang::get('l4cp-support::messages.update.success'))) ? : Redirect::to('admin/support/autoreplies/' . $autoreply->id . '/edit')->with('success', Lang::get('l4cp-support::messages.update.success')) : Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/autoreplies/' . $autoreply->id . '/edit')->with('error', Lang::get('l4cp-support::messages.update.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/autoreplies/' . $autoreply->id . '/edit')->withInput()->withErrors($validator);
	}


    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function deleteIndex($autoreply)
    {
		$id = $autoreply->id;
		if(!$autoreply->delete()) return Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));
		$autoreply=TicketDeps::find($id);
        return empty($autoreply) ? Api::json(array('result'=>'success')) : Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));        
    }

    /**
     * Show a list of all the blog posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
		$list = TicketAutoreply::join('ticket_autoreply_deps', 'ticket_autoreply_deps.ticket_autoreply_id', '=', 'ticket_autoreply.id')
					->join('ticket_deps', 'ticket_autoreply_deps.ticket_deps_id', '=', 'ticket_deps.id')
                    ->select(DB::raw('ticket_autoreply.id, ticket_autoreply.title, ticket_autoreply.content, group_concat(ticket_deps.name SEPARATOR \', \') as depname'))->groupBy(DB::raw('ticket_autoreply.id, ticket_autoreply.title, ticket_autoreply.content'));

        if(Api::Enabled()){
			$u=$list->get();
			return Api::make($u->toArray());
		} else return Datatables::of($list)

        ->edit_column('title', '{{{ Filter::filter(Str::limit($title, 100, "..."), "***") }}} ')
        ->edit_column('content', '{{{ Filter::filter(Str::limit(strip_tags($content), 100, "..."), "***") }}} ')

        ->add_column('actions', '<div class="btn-group"><a href="{{{ URL::to(\'admin/support/autoreplies/\' . $id . \'/edit\' ) }}}" class="btn btn-primary btn-sm modalfy" >{{{ Lang::get(\'button.edit\') }}}</a>
                <a data-method="delete" data-row="{{{  $id }}}" data-table="replies"  href="{{{ URL::to(\'admin/support/autoreplies/\' . $id . \'\' ) }}}" class="confirm-ajax-update btn btn-sm btn-danger">{{{ Lang::get(\'button.delete\') }}}</a></div>
            ')

        ->make();
    }

}