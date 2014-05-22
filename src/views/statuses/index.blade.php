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
			<a href="{{{ URL::to('admin/support/statuses/create') }}}" class="btn  btn-info modalfy"><span class="fa fa-plus"></span> {{{ Lang::get('button.create') }}}</a>
		</div>
	</div>

	@include(Theme::path('admin/dt-loading'))

	<div id="statuses-container" class="dt-wrapper">
		<table id="statuses" class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th></th>
					<th class="col-md-8">{{{ Lang::get('l4cp-support::core.title') }}}</th>
					<th class="col-md-2">{{{ Lang::get('l4cp-support::core.auto_close') }}}</th>
					<th class="col-md-2">{{{ Lang::get('table.actions') }}}</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
@stop
@include(Theme::path('admin/layouts/sidebar-left'))

@section('scripts')
	<script src="{{{ asset('assets/js/jquery.dataTables.min.js') }}}"></script>
	<script src="{{{ asset('assets/js/datatables.js') }}}"></script>
	<script type="text/javascript">
		dtLoad('#statuses', 'statuses/data', '', 'td:eq(1), th:eq(1)', '', 'false', 'true', [null, null, null, null]);
	</script>
@stop