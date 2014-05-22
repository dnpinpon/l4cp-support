<?php
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;


class SupportDepartmentController extends BaseController {


    /**
     * Post Model
     * @var Post
     */
    protected $department;

    /**
     * Inject the models.
     * @param Post $post
     */
    public function __construct(TicketDeps $department)
    {
        parent::__construct();
        $this->department = $department;
    }


     /**
     * Show a list of all the blog posts.
     *
     * @return View
     */
    public function getIndex()
    {
       
		$title = Lang::get('l4cp-support::core.departments');
		return Theme::make('l4cp-support::departments/index', compact('title'));

    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$admins=Support::getAdmins();
		$title = Lang::get('l4cp-support::core.create_a_new_department');
		return Theme::make('l4cp-support::departments/create_edit', compact('title', 'admins'));
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
            'flags'   => 'required',
        );

		$validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
			$this->department->name = Input::get('name');
			$this->department->description = Input::get('description');
			$this->department->email = Input::get('email');
            $this->department->pop_host = Input::get('pop_host');
            $this->department->pop_port = Input::get('pop_port');
            $this->department->pop_user = Input::get('pop_user');
            $this->department->pop_pass = Input::get('pop_pass');
            $this->department->clients_only = (int)Input::get('clients_only');
            $this->department->auto_respond = (int)Input::get('auto_respond');
            $this->department->hidden = (int)Input::get('hidden');
            $this->department->sort = (int)Input::get('sort');
			$this->department->saveFlags(Input::get( 'flags' ));

   
			
			if($this->department->save())
            {
                return Api::to(array('success', Lang::get('l4cp-support::messages.create.success'))) ? : Redirect::to('admin/support/departments/' . $this->department->id . '/edit')->with('success', Lang::get('l4cp-support::messages.create.success'));
            } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/departments/create')->with('error', Lang::get('l4cp-support::messages.create.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/departments/create')->withInput()->withErrors($validator);
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param $post
     * @return Response
     */
	public function getEdit($department)
	{

 		$admins=Support::getAdmins();
		$title = Lang::get('l4cp-support::core.department_update');
        return Theme::make('l4cp-support::departments/create_edit', compact('department', 'title', 'admins'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param $post
     * @return Response
     */
	public function putEdit($department)
	{

        $rules = array(
            'name'   => 'required|min:3',
             'flags'   => 'required',
       );

        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
			$department->name = Input::get('name');
			$department->description = Input::get('description');
			$department->email = Input::get('email');
            $department->pop_host = Input::get('pop_host');
            $department->pop_port = Input::get('pop_port');
            $department->pop_user = Input::get('pop_user');
            $department->pop_pass = Input::get('pop_pass');
            $department->clients_only = (int)Input::get('clients_only');
            $department->auto_respond = (int)Input::get('auto_respond');
            $department->hidden = (int)Input::get('hidden');
            $department->sort = (int)Input::get('sort');
			$department->saveFlags(Input::get( 'flags' ));

            return $department->save() ? Api::to(array('success', Lang::get('l4cp-support::messages.update.success'))) ? : Redirect::to('admin/support/departments/' . $department->id . '/edit')->with('success', Lang::get('l4cp-support::messages.update.success')) : Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/departments/' . $department->id . '/edit')->with('error', Lang::get('l4cp-support::messages.update.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/departments/' . $department->id . '/edit')->withInput()->withErrors($validator);
	}


    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function deleteIndex($department)
    {
		$id = $department->id;
		if(!$department->delete()) return Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));
		$department=TicketDeps::find($id);
        return empty($department) ? Api::json(array('result'=>'success')) : Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));        
    }

    /**
     * Show a list of all the blog posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
        $list = TicketDeps::select(array('id','name', 'description'))->orderBy('sort');

        if(Api::Enabled()){
			$u=$list->get();
			return Api::make($u->toArray());
		} else return Datatables::of($list)

        ->edit_column('description', '{{{ Filter::filter(Str::limit(strip_tags($description), 100, "..."), "*") }}} ')

        ->add_column('actions', '<div class="btn-group"><a href="{{{ URL::to(\'admin/support/departments/\' . $id . \'/edit\' ) }}}" class="btn btn-primary btn-sm modalfy" >{{{ Lang::get(\'button.edit\') }}}</a>
                <a data-method="delete" data-row="{{{  $id }}}" data-table="departments"  href="{{{ URL::to(\'admin/support/departments/\' . $id . \'\' ) }}}" class="confirm-ajax-update btn btn-sm btn-danger">{{{ Lang::get(\'button.delete\') }}}</a></div>
            ')

        ->make();
    }

}