@section('main-nav-post')
	<li class="dropdown{{ (Request::is('admin/support*') ? ' active' : '') }}">
		<a id="nav_support" class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('admin/support') }}}">
			<span class="fa fa-fw fa-question-circle"></span> {{{ Lang::get('l4cp-support::core.support') }}} <span class="caret"></span>
		</a>
		<ul aria-labelledby="nav_support" class="dropdown-menu">
			<li {{ (Request::is('admin/support/') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support') }}}">{{{ Lang::get('l4cp-support::core.active') }}}</a></li>
			@foreach(Support::getStatuses() as $id => $status)
				<li {{ !empty($tickets) ? ($id == $tickets->status ? "class=\"active\"" : null) : null  }} {{ (Request::is('admin/support/'.Str::slug(strtolower($status))) ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/'.Str::slug(strtolower($status))) }}}">{{{ $status }}}</a></li>
			@endforeach
		
			<li class="divider"></li>
			<li {{ (Request::is('admin/support/departments') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/departments') }}}"><span class="fa fa-lg fa-list-alt fa-fw"></span>  {{{ Lang::get('l4cp-support::core.departments') }}}</a></li>
			<li {{ (Request::is('admin/support/spam') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/spam') }}}"><span class="fa fa-shield fa-fw"></span>  {{{ Lang::get('l4cp-support::core.spam_filter') }}}</a></li>
			<li {{ (Request::is('admin/support/autoreplies') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/autoreplies') }}}"><span class="fa fa-mail-reply-all fa-fw"></span>  {{{ Lang::get('l4cp-support::core.auto_replies') }}}</a></li>
			<li {{ (Request::is('admin/support/statuses') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/statuses') }}}"><span class="fa fa-flag fa-fw"></span>  {{{ Lang::get('l4cp-support::core.statuses') }}}</a></li>
			<li {{ (Request::is('admin/support/escalations') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/escalations') }}}"><span class="fa fa-level-up fa-fw"></span>  {{{ Lang::get('l4cp-support::core.escalations') }}}</a></li>
			
		</ul>
	</li>
@stop