


<div class="list-group" style="margin: 5px">
	<a href="#" class="list-group-item list-group-item-info">{{{ Lang::get('l4cp-support::core.support') }}}</a>
	<a href="{{{ URL::to('admin/support') }}}" class="list-group-item {{ (Request::is('admin/support') ? ' active' : '') }}">{{{ Lang::get('l4cp-support::core.active') }}}</a>
	@foreach(Support::getStatusesCount() as $id => $status)
		<a class="list-group-item {{ !empty($tickets) ? ($status->id == $tickets->status ? "active" : null) : null  }} {{ (Request::is('admin/support/'.Str::slug(strtolower($status->title))) ? ' active' : '') }}" href="{{{ URL::to('admin/support/'.Str::slug(strtolower($status->title))) }}}">
			{{{ $status->title }}}
			@if($status->total > 0)<span class="badge pull-right">{{ $status->total }}</span>@endif
		</a>
	@endforeach
</div>

<br/>


<div class="list-group" style="margin: 5px">
	<a href="#" class="list-group-item list-group-item-info">{{{ Lang::get('core.settings') }}}</a>

	<a href="{{{ URL::to('admin/support/departments') }}}" class="list-group-item{{ Request::is('admin/support/departments') ? ' active' : ''}}"><span class="fa fa-lg fa-list-alt fa-fw"></span>  {{{ Lang::get('l4cp-support::core.departments') }}}</a></li>
	<a href="{{{ URL::to('admin/support/spam') }}}" class="list-group-item{{ Request::is('admin/support/spam') ? ' active' : ''}}"><span class="fa fa-lg fa-shield fa-fw"></span>  {{{ Lang::get('l4cp-support::core.spam_filter') }}}</a></li>
	<a href="{{{ URL::to('admin/support/autoreplies') }}}" class="list-group-item{{ Request::is('admin/support/autoreplies') ? ' active' : ''}}"><span class="fa fa-lg fa-mail-reply-all fa-fw"></span>  {{{ Lang::get('l4cp-support::core.auto_replies') }}}</a></li>
	<a href="{{{ URL::to('admin/support/statuses') }}}" class="list-group-item{{ Request::is('admin/support/statuses') ? ' active' : ''}}"><span class="fa fa-lg fa-flag fa-fw"></span>  {{{ Lang::get('l4cp-support::core.statuses') }}}</a></li>
	<a href="{{{ URL::to('admin/support/escalations') }}}" class="list-group-item{{ Request::is('admin/support/escalations') ? ' active' : ''}}"><span class="fa fa-lg fa-level-up fa-fw"></span>  {{{ Lang::get('l4cp-support::core.escalations') }}}</a></li>

</div>
