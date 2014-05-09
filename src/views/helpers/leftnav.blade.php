<div class="list-group" style="margin: 5px">
	<a href="#" class="list-group-item list-group-item-info">{{{ Lang::get('l4cp-support::core.support') }}}</a>
	<a href="{{{ URL::to('admin/support') }}}" class="list-group-item {{ (Request::is('admin/support') ? ' active' : '') }}">{{{ Lang::get('l4cp-support::core.active') }}}</a>
	@foreach(Support::getStatusesCount() as $id => $status)
		<a class="list-group-item {{ !empty($tickets) ? ($status->id == $tickets->status ? "active" : null) : null  }} 
		{{ (Request::is('admin/support/'.Str::slug(strtolower($status->title))) ? ' active' : '') }}
		{{ isset($value) && $value=='admin/support/'.Str::slug(strtolower($status->title)) ? 'active' : null }} " href="{{{ URL::to('admin/support/'.Str::slug(strtolower($status->title))) }}}">
			{{{ $status->title }}}
			@if($status->total > 0)<span class="badge pull-right">{{ $status->total }}</span>@endif
		</a>
	@endforeach
</div>