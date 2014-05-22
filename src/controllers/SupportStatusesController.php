<?php
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;


class SupportStatusesController extends BaseController {


    /**
     * Post Model
     * @var Post
     */
    protected $statuses;

    /**
     * Inject the models.
     * @param Post $post
     */
    public function __construct(TicketStatuses $statuses)
    {
        parent::__construct();
        $this->statuses = $statuses;
    }


     /**
     * Show a list of all the blog posts.
     *
     * @return View
     */
    public function getIndex()
    {
		$title = Lang::get('l4cp-support::core.statuses');
		return Theme::make('l4cp-support::statuses/index', compact('title'));
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$title = Lang::get('l4cp-support::core.create_a_new_statuses');
		return Theme::make('l4cp-support::statuses/create_edit', compact('title'));
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
        if ($validator->passes())
        {

			if((int)Input::get('default_flag') == 1) TicketStatuses::where('default_flag', '=', '1')->update(array('default_flag' => '0'));
			if((int)Input::get('default_button') == 1) TicketStatuses::where('default_button', '=', '1')->update(array('default_button' => '0'));
			if((int)Input::get('default_category') == 1) TicketStatuses::where('default_category', '=', '1')->update(array('default_category' => '0'));
			if((int)Input::get('close_status') == 1) TicketStatuses::where('close_status', '=', '1')->update(array('close_status' => '0'));

            $this->statuses->title = Input::get('title');
            $this->statuses->color = Input::get('color');
            $this->statuses->sort = (int)Input::get('sort');
            $this->statuses->show_active = (int)Input::get('show_active');
            $this->statuses->default_flag = (int)Input::get('default_flag');
            $this->statuses->default_category = (int)Input::get('default_category');
            $this->statuses->default_button = (int)Input::get('default_button');
            $this->statuses->auto_close = (int)Input::get('auto_close');
            $this->statuses->close_status = (int)Input::get('close_status');


            if($this->statuses->save())
            {
                return Api::to(array('success', Lang::get('l4cp-support::messages.create.success'))) ? : Redirect::to('admin/support/statuses/' . $this->statuses->id . '/edit')->with('success', Lang::get('l4cp-support::messages.create.success'));
            } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/statuses/create')->with('error', Lang::get('l4cp-support::messages.create.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/statuses/create')->withInput()->withErrors($validator);
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param $post
     * @return Response
     */
	public function getEdit($statuses)
	{

        $title = Lang::get('l4cp-support::core.statuses_update');
        return Theme::make('l4cp-support::statuses/create_edit', compact('statuses', 'title'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param $post
     * @return Response
     */
	public function putEdit($statuses)
	{

        $rules = array(
            'title'   => 'required|min:3',
        );

        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
			if((int)Input::get('close_status') == 1) TicketStatuses::where('close_status', '=', '1')->update(array('close_status' => '0'));
   			if((int)Input::get('default_flag') == 1) TicketStatuses::where('default_flag', '=', '1')->update(array('default_flag' => '0'));
   			if((int)Input::get('default_button') == 1) TicketStatuses::where('default_button', '=', '1')->update(array('default_button' => '0'));
   			if((int)Input::get('default_category') == 1) TicketStatuses::where('default_category', '=', '1')->update(array('default_category' => '0'));
		
			$statuses->title = Input::get('title');
            $statuses->color = Input::get('color');
            $statuses->sort = (int)Input::get('sort');
            $statuses->show_active = (int)Input::get('show_active');
            $statuses->default_flag = (int)Input::get('default_flag');
            $statuses->default_button = (int)Input::get('default_button');
            $statuses->default_category = (int)Input::get('default_category');
            $statuses->auto_close = (int)Input::get('auto_close');
            $statuses->close_status = (int)Input::get('close_status');

            return $statuses->save() ? Api::to(array('success', Lang::get('l4cp-support::messages.update.success'))) ? : Redirect::to('admin/support/statuses/' . $statuses->id . '/edit')->with('success', Lang::get('l4cp-support::messages.update.success')) : Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/statuses/' . $statuses->id . '/edit')->with('error', Lang::get('l4cp-support::messages.update.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/statuses/' . $statuses->id . '/edit')->withInput()->withErrors($validator);
	}


    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function deleteIndex($statuses)
    {
		$id = $statuses->id;
		if(!$statuses->delete()) return Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));
		$statuses=TicketDeps::find($id);
        return empty($statuses) ? Api::json(array('result'=>'success')) : Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));        
    }

    /**
     * Show a list of all the blog posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
        $list = TicketStatuses::select(array('id','title', 'auto_close', 'color'))->orderBy('sort');

        if(Api::Enabled()){
			$u=$list->get();
			return Api::make($u->toArray());
		} else return Datatables::of($list)

		->edit_column('auto_close', '{{{ $auto_close ? "Yes" : "No" }}} ')
		->edit_column('title', '@if($color)<span style="color: {{{ $color }}}">{{{ $title }}}</span>@else{{{ $title }}}@endif')
        ->add_column('actions', '<div class="btn-group"><a href="{{{ URL::to(\'admin/support/statuses/\' . $id . \'/edit\' ) }}}" class="btn btn-primary btn-sm modalfy" >{{{ Lang::get(\'button.edit\') }}}</a>
                <a data-method="delete" data-row="{{{  $id }}}" data-table="statuses"  href="{{{ URL::to(\'admin/support/statuses/\' . $id . \'\' ) }}}" class="confirm-ajax-update btn btn-sm btn-danger">{{{ Lang::get(\'button.delete\') }}}</a></div>
            ')
		->remove_column('color')
        ->make();
    }

}