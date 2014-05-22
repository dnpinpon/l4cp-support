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
			<a href="{{{ URL::to('admin/support/create') }}}" class="btn  btn-info modalfy"><span class="fa fa-plus"></span> {{{ Lang::get('button.create') }}}</a>
		</div>
	</div>

	@include(Theme::path('admin/dt-loading'))

	<div id="tickets-container" class="dt-wrapper">
		<table id="tickets" class="table table-responsive table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th>{{{ Lang::get('core.id') }}}</th>
					<th style="width: 15px"><span class="fa fa-flag"></span></th>
					<th>{{{ Lang::get('l4cp-support::core.name') }}}</th>
					<th>{{{ Lang::get('l4cp-support::core.title') }}}</th>
					<th>{{{ Lang::get('l4cp-support::core.department') }}}</th>
					@if(empty($slug) || $department->default_flag == true)<th>{{{ Lang::get('l4cp-support::core.status') }}}</th>@endif
					<th>{{{ Lang::get('admin/users/table.updated_at') }}}</th>
					<th class="col-md-2" style="width: 180px">{{{ Lang::get('table.actions') }}}</th>
				</tr>
			</thead> 

			<tbody>
			</tbody>
		</table>
	</div>
@stop
@include(Theme::path('admin/layouts/sidebar-left'))

@section('head-scripts-pre')
	<script src="{{{ asset('assets/js/select2.min.js') }}}"></script>
@stop

@section('head-scripts')
	<script src="{{{ asset('assets/js/bootstrap-wysiwyg.js') }}}"></script>
@stop

@section('styles')
	<link rel="stylesheet" href="{{{ asset('assets/css/select2.css') }}}">
@stop

@section('scripts')
	<script src="{{{ asset('assets/js/jquery.dataTables.min.js') }}}"></script>
	<script src="{{{ asset('assets/js/datatables.js') }}}"></script>
	<script type="text/javascript">
		@if(!empty($slug))
			dtLoad('#tickets', "{{{ URL::to('admin/support/data/'.$slug) }}}", '', 'td:eq(2), th:eq(2)', '', 'false', null, [null, null, null, null, null, null, null]);
		@else
			dtLoad('#tickets', "{{{ URL::to('admin/support/data') }}}", '', 'td:eq(2), th:eq(2)', '', 'false', null, [null, null, null, null, null, null, null, null]);
		@endif
	</script>
@stop