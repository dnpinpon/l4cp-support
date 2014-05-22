<?php
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;


class SupportSpamfilterController extends BaseController {


    /**
     * Post Model
     * @var Post
     */
    protected $spam;

    /**
     * Inject the models.
     * @param Post $post
     */
    public function __construct(TicketSpam $spam)
    {
        parent::__construct();
        $this->spam = $spam;
    }


     /**
     * Show a list of all the blog posts.
     *
     * @return View
     */
    public function getIndex()
    {
       
		$title = Lang::get('l4cp-support::core.spam_filter');
		return Theme::make('l4cp-support::spam/index', compact('title'));

    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$title = Lang::get('l4cp-support::core.create_a_new_spam');
		return Theme::make('l4cp-support::spam/create_edit', compact('title'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postCreate()
	{
        $rules = array(
            'type'   => 'required',
            'content'   => 'required',
        );

		$validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {

            $this->spam->type = Input::get('type');
            $this->spam->content = Input::get('content');

            if($this->spam->save())
            {
                return Api::to(array('success', Lang::get('l4cp-support::messages.create.success'))) ? : Redirect::to('admin/support/spam/' . $this->spam->id . '/edit')->with('success', Lang::get('l4cp-support::messages.create.success'));
            } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/spam/create_edit')->with('error', Lang::get('l4cp-support::messages.create.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.create.error'))) ? : Redirect::to('admin/support/spam/create_edit')->withInput()->withErrors($validator);
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param $post
     * @return Response
     */
	public function getEdit($spam)
	{

        $title = Lang::get('l4cp-support::core.spam_update');
        return Theme::make('l4cp-support::spam/create_edit', compact('spam', 'title'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param $post
     * @return Response
     */
	public function putEdit($spam)
	{

        $rules = array(
            'type'   => 'required',
            'content'   => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {
            $spam->type = Input::get('type');
            $spam->content = Input::get('content');

            return $spam->save() ? Api::to(array('success', Lang::get('l4cp-support::messages.update.success'))) ? : Redirect::to('admin/support/spam/' . $spam->id . '/edit')->with('success', Lang::get('l4cp-support::messages.update.success')) : Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/spam/' . $spam->id . '/edit')->with('error', Lang::get('l4cp-support::messages.update.error'));
        } else return Api::to(array('error', Lang::get('l4cp-support::messages.update.error'))) ? : Redirect::to('admin/support/spam/' . $spam->id . '/edit')->withInput()->withErrors($validator);
	}


    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function deleteIndex($spam)
    {
		$id = $spam->id;
		if(!$spam->delete()) return Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));
		$spam=TicketDeps::find($id);
        return empty($spam) ? Api::json(array('result'=>'success')) : Api::json(array('result'=>'error', 'error' =>Lang::get('core.delete_error')));        
    }

    /**
     * Show a list of all the blog posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
        $list = TicketSpam::select(array('id','type', 'content'));

        if(Api::Enabled()){
			$u=$list->get();
			return Api::make($u->toArray());
		} else return Datatables::of($list)


        ->edit_column('content', '{{{ Str::limit($content, 100, "...") }}} ')
        ->add_column('actions', '<div class="btn-group"><a href="{{{ URL::to(\'admin/support/spam/\' . $id . \'/edit\' ) }}}" class="btn btn-primary btn-sm modalfy" >{{{ Lang::get(\'button.edit\') }}}</a>
                <a data-method="delete" data-row="{{{  $id }}}" data-table="spam"  href="{{{ URL::to(\'admin/support/spam/\' . $id . \'\' ) }}}" class="confirm-ajax-update btn btn-sm btn-danger">{{{ Lang::get(\'button.delete\') }}}</a></div>
            ')

        ->make();
    }

}