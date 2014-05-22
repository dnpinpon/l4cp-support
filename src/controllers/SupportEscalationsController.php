<?php
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;


class SupportEscalationsController extends BaseController {


    /**
     * Post Model
     * @var Post
     */
    protected $escalations;


    /**
     * Inject the models.
     * @param Post $post
     */
    public function __construct(TicketEscalations $escalations)
    {
        parent::__construct();
        $this->escalations = $escalations;
    }


     /**
     * Show a list of all the blog posts.
     *
     * @return View
     */
    public function getIndex()
    {
       
		$title = Lang::get('l4cp-support::core.escalations');
		return Theme::make('l4cp-support::escalations/index', compact('title'));

    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$title = Lang::get('l4cp-support::core.create_a_new_escalations');
		return Theme::make('l4cp-support::escalations/create_edit', compact('title'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postCreate()
	{
        $rules = array(
            'name'   => 'required|min:3',
        );

		$validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {

            $this->escalations->name = Input::get('name');
            $this->escalations->delay = (int)Input::get('delay');
            $this->escalations->reply = Input::get('reply');
            $this->escalations->notify_admins = (int)Input::get('notify_admins');
            $this->escalations->flag = (int)Input::get('flag');
            $this->escalations->priority = json_encode(Input::get('priorities'));
			$this->escalations->new_status = (int)Input::get('new_status');
            $this->escalations->new_priority = (int)Input::get('new_priority');
            $this->escalations->new_department = (int)Input::get('new_department');
			$this->escalations->saveDeps(Input::get( 'departments' ));
			$this->escalations->saveStatuses(Input::get( 'statuses' ));
			$this->escalations->saveFlags(explode(',',Input::get('flags' )));

            if($this->escalations->save())
            {
                return Api::to(array('success', Lang::get('l4cp-support::messages.create.success'))) ? : Redirect::to('admin/support/escalations/' . $this->escalations->id . '/edit')->with('success', Lang::get('l4cp-support::messages.create.success'));
            } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/escalations/create')->with('error', Lang::get('l4cp-support::messages.create.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/escalations/create')->withInput()->withErrors($validator);
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param $post
     * @return Response
     */
	public function getEdit($escalations)
	{

		$title = Lang::get('l4cp-support::core.escalations_update');
        return Theme::make('l4cp-support::escalations/create_edit', compact('escalations', 'title'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param $post
     * @return Response
     */
	public function putEdit($escalations)
	{

        $rules = array(
            'name'   => 'required|min:3',
        );

        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
            $escalations->name = Input::get('name');
            $escalations->delay = (int)Input::get('delay');
            $escalations->reply = Input::get('reply');
            $escalations->notify_admins = (int)Input::get('notify_admins');
            $escalations->flag = (int)Input::get('flag');
            $escalations->priority = json_encode(Input::get('priorities'));
            $escalations->new_status = (int)Input::get('new_status');
            $escalations->new_priority = (int)Input::get('new_priority');
            $escalations->new_department = (int)Input::get('new_department');
			$escalations->saveDeps(Input::get( 'departments' ));
			$escalations->saveStatuses(Input::get( 'statuses' ));
			$escalations->saveFlags(explode(',',Input::get( 'flags' )));

            return $escalations->save() ? Api::to(array('success', Lang::get('l4cp-support::messages.update.success'))) ? : Redirect::to('admin/support/escalations/' . $escalations->id . '/edit')->with('success', Lang::get('l4cp-support::messages.update.success')) : Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/escalations/' . $escalations->id . '/edit')->with('error', Lang::get('l4cp-support::messages.update.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/escalations/' . $escalations->id . '/edit')->withInput()->withErrors($validator);
	}


    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function deleteIndex($escalations)
    {
		$id = $escalations->id;
		if(!$escalations->delete()) return Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));
		$escalations=TicketDeps::find($id);
        return empty($escalations) ? Api::json(array('result'=>'success')) : Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));        
    }

    /**
     * Show a list of all the blog posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
        $list = TicketEscalations::select(array('id','name','delay'));

        if(Api::Enabled()){
			$u=$list->get();
			return Api::make($u->toArray());
		} else return Datatables::of($list)

		->edit_column('delay', '{{{ $delay ? : "None" }}}')
        ->add_column('actions', '<div class="btn-group"><a href="{{{ URL::to(\'admin/support/escalations/\' . $id . \'/edit\' ) }}}" class="btn btn-primary btn-sm modalfy" >{{{ Lang::get(\'button.edit\') }}}</a>
                <a data-method="delete" data-row="{{{  $id }}}" data-table="escalations"  href="{{{ URL::to(\'admin/support/escalations/\' . $id . \'\' ) }}}" class="confirm-ajax-update btn btn-sm btn-danger">{{{ Lang::get(\'button.delete\') }}}</a></div>
            ')

        ->make();
    }

}