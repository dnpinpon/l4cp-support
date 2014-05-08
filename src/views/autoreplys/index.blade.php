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
			<a href="{{{ URL::to('admin/support/autoreplies/create') }}}" class="btn btn-small btn-info modalfy"><span class="fa fa-lg fa-plus-square"></span> {{{ Lang::get('button.create') }}}</a>
		</div>
	</div>

	@include(Theme::path('admin/dt-loading'))

	<div id="replies-container" class="dt-wrapper">
		<table id="replies" class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th></th>
					<th class="col-md-3">{{{ Lang::get('l4cp-support::core.title') }}}</th>
					<th class="col-md-4">{{{ Lang::get('l4cp-support::core.content') }}}</th>
					<th class="col-md-3">{{{ Lang::get('l4cp-support::core.department') }}}</th>
					<th class="col-md-2">{{{ Lang::get('table.actions') }}}</th>
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
	dtLoad('#replies', 'autoreplies/data', 'td:eq(2), th:eq(2)', 'td:eq(3), th:eq(3)');
</script>
@stop