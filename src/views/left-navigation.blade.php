<ul class="nav nav-sidebar nav-pills nav-stacked">
	<li {{ (Request::is('admin/support') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support') }}}"><span class="fa fa-lg fa-question-circle fa-fw"></span>  {{{ Lang::get('l4cp-support::core.support') }}}</a>
	
		<ul class="nav nav-sidebar nav-pills nav-stacked" style="padding-left: 30px">
			@foreach(Support::getStatusesCount() as $id => $status)
				<li {{ !empty($tickets) ? ($status->id == $tickets->status ? "class=\"active\"" : null) : null  }} {{ (Request::is('admin/support/'.Str::slug(strtolower($status->title))) ? ' class="active"' : '') }}>
					
					<a href="{{{ URL::to('admin/support/'.Str::slug(strtolower($status->title))) }}}">
						-   {{{ $status->title }}}
						@if($status->total > 0)<span class="badge pull-right">{{ $status->total }}</span>@endif
					</a>
				</li>
			@endforeach
		</ul>
	</li>
</ul>
<ul class="nav nav-sidebar nav-pills nav-stacked">
	<li class="list-group-item list-group-item-info">Settings</li>

	<li {{ (Request::is('admin/support/departments') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/departments') }}}"><span class="fa fa-lg fa-list-alt fa-fw"></span>  {{{ Lang::get('l4cp-support::core.departments') }}}</a></li>
	<li {{ (Request::is('admin/support/spam') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/spam') }}}"><span class="fa fa-lg fa-shield fa-fw"></span>  {{{ Lang::get('l4cp-support::core.spam_filter') }}}</a></li>
	<li {{ (Request::is('admin/support/autoreplies') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/autoreplies') }}}"><span class="fa fa-lg fa-mail-reply-all fa-fw"></span>  {{{ Lang::get('l4cp-support::core.auto_replies') }}}</a></li>
	<li {{ (Request::is('admin/support/statuses') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/statuses') }}}"><span class="fa fa-lg fa-flag fa-fw"></span>  {{{ Lang::get('l4cp-support::core.statuses') }}}</a></li>
	<li {{ (Request::is('admin/support/escalations') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/support/escalations') }}}"><span class="fa fa-lg fa-level-up fa-fw"></span>  {{{ Lang::get('l4cp-support::core.escalations') }}}</a></li>

</ul>
