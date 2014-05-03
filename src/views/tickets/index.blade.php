@extends(Theme::path('admin/layouts/default'))

@section('title')
	{{{ $title }}} :: @parent
@stop

@section('left-layout-nav')
	@include(Theme::path('l4cp-support::left-navigation'))
@stop

@section('left-layout-content')
	<div class="page-header clearfix">
		<div class="pull-left"><h3>{{{ $title }}}</h3></div>
		<div class="pull-right">
			<a href="{{{ URL::to('admin/support/create') }}}" class="btn btn-small btn-info modalfy"><span class="fa fa-lg fa-plus-square"></span> {{{ Lang::get('button.create') }}}</a>
		</div>
	</div>

	@include(Theme::path('admin/dt-loading'))

	<div id="tickets-container" class="dt-wrapper">
		<table id="tickets" class="table table-responsive table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th></th>
					<th class="" style="width: 15px"></th>
					<th class="">{{{ Lang::get('l4cp-support::core.name') }}}</th>
					<th class="">{{{ Lang::get('l4cp-support::core.title') }}}</th>
					<th class="">{{{ Lang::get('l4cp-support::core.department') }}}</th>
					@if(empty($slug) || $department->default_flag == true)<th class="">{{{ Lang::get('l4cp-support::core.status') }}}</th>@endif
					<th class="">{{{ Lang::get('core.created') }}}</th>
					<th class="col-md-2" style="width: 180px">{{{ Lang::get('table.actions') }}}</th>
				</tr>
			</thead> 

			<tbody>
			</tbody>
		</table>
	</div>
@stop
@include(Theme::path('admin/left-layout'))

@section('scripts')
<script type="text/javascript">
@if(!empty($slug))
	dtLoad('#tickets', "{{{ URL::to('admin/support/data/'.$slug) }}}", '', 'td:eq(2), th:eq(2)');
@else
	dtLoad('#tickets', "{{{ URL::to('admin/support/data') }}}", '', 'td:eq(2), th:eq(2)');
@endif
</script>
@stop