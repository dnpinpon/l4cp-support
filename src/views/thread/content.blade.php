<div class="page-header clearfix">
	<div class="pull-left">
		<h3>{{{ Filter::filter(Str::limit(strip_tags($tickets->title), 46)) }}}</h3>
		Last updated: {{{ Carbon::parse($tickets->updated_at)->diffForHumans() }}}
	</div>


	<div class="pull-right">
		@if($tickets->status != $button->id)<a data-method="get" href="{{{ URL::to('admin/support/'.$tickets->id.'/status/'.$button->id) }}}" class="btn btn-small btn-success basic-confirm">{{{Lang::get('l4cp-support::core.mark')}}} {{{ $button->title }}}</a>@endif
		<a href="{{{ URL::to('admin/support/'.$tickets->id.'/edit') }}}" class="btn btn-small btn-primary modalfy">{{{ Lang::get('button.edit') }}}</a>
		<a href="{{{ URL::to('admin/support/'.$tickets->id.'/thread/create') }}}" class="btn btn-small btn-info modalfy"><span class="fa fa-lg fa-plus-square"></span> {{{ Lang::get('button.reply') }}}</a>
	</div>
</div>


@foreach($replies as $t => $r)
	<div class="popover {{{ $r->userid != $user->id ? 'left' : 'right'}}} ">
		<div class="arrow"><span class="glyphicon pull-left popover-icon"><img alt="{{{ $r->useremail ? : $r->email }}}" src="{{ Gravatar::src($r->useremail ? : $r->email, 40) }}"></span></div>
		<h3 class="popover-title"><strong>{{{ $r->displayname }}}</strong>, {{{ Carbon::parse($r->updated_at)->diffForHumans() }}}</h3>
		<div class="popover-content">
			<p>{{ Filter::filter(nl2br(strip_tags($r->content))) }}</p>
		</div>
	</div>
@endforeach

<div class=" popover {{{ isset($admin) && $admin->displayname ? 'left' : 'right'}}}">
	<div class="arrow"><span class="glyphicon pull-left popover-icon"><img alt="{{{ (isset($admin)  ? $admin->email: $user->email ) ? : $tickets->email}}}" src="{{ Gravatar::src((isset($admin)  ? $admin->email : $user->email ) ? : $tickets->email, 40)}}"></span></div>
	<h3 class="popover-title"><strong>{{{ (isset($admin) ? $admin->displayname: $user->displayname ) ? : $tickets->name}}}</strong>, {{{ Carbon::parse($tickets->created_at)->diffForHumans() }}}</h3>
	<div class="popover-content">
		<p>{{ Filter::filter(nl2br(strip_tags($tickets->message))) }}</p>
	</div>
</div>


<!--
{{{ $tickets->user_id }}}
{{{ $tickets->admin_id }}}
{{{ $tickets->department_id }}}

{{{ $tickets->name }}}
{{{ $tickets->email }}}

{{{ $tickets->title }}}
{{{ $tickets->attachment }}}
{{{ $tickets->message }}}
{{{ $tickets->priority }}}
{{{ $tickets->status }}}


-->