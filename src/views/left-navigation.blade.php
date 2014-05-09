

<div id="support-left-nav">
	@include(Theme::path('l4cp-support::helpers/leftnav'))
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

<script type="text/javascript">
		$.fn.poller('add',{'id':'#support-left-nav', 'type':'template', 'func':'l4cp-support::helpers/leftnav', 'value':'{{{ Request::path() }}}',  'ratio': '1'});
</script>