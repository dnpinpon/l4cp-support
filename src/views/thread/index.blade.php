@extends(Theme::path('admin/layouts/default'))

@section('title')
	{{{ Filter::filter($tickets->title) }}} :: @parent
@stop

@section('left-layout-nav')
	@include(Theme::path('l4cp-support::left-navigation'))
@stop

@section('left-layout-content')
<style type="text/css">
	 .popover {
	  position: relative;
	  display: block;
	  margin: 20px;
	margin-right:50px;
	  max-width: 800px;
	}
	.popover.right{
		left: 50px;
	}
	.popover.left{
		margin-left:0px;
	}

	.popover.right .popover-icon{
		margin-top: -20px;
		margin-left: -50px;
	}
	.popover.left .popover-icon{
		margin-top: -20px;
		margin-left: 10px;
	}
</style>
	<div id="ticket-update">
		@include(Theme::path('l4cp-support::thread/content'))
	</div>

	<script type="text/javascript">
		$.fn.poller('add',{'id':'#ticket-update', 'type':'plugin', 'ratio': '1', 'func': 'Support::getTicketThread', 'value': {{ json_encode($tickets->toArray()) }}});
	</script>


@stop
@include(Theme::path('admin/layouts/sidebar-left'))

@section('scripts')
	<script type="text/javascript">
	</script>
@stop

@section('head-scripts')
	<script src="{{{ asset('assets/js/bootstrap-wysiwyg.js') }}}"></script>
@stop
