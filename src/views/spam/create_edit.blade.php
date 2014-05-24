@extends(Theme::path('admin/layouts/modal'))

@section('title')
	{{{ $title }}}
@stop

@section('content')
	@if ($message = Session::get('success'))
	<script type="text/javascript">
		if(parent.$('#spam').html()){
			var oTable = parent.$('#spam').dataTable();
			oTable.fnReloadAjax();
		}
		closeModel();
	</script>
	@else

	@if (isset($spam))
		{{ Form::open(array('method' => 'put','url' => URL::to('admin/support/spam/' . $spam->id . '/edit'), 'class' => 'form-horizontal form-ajax')) }}
	@else
		{{ Form::open(array('class' => 'form-horizontal form-ajax')) }}
	@endif

		<div class="form-group {{{ $errors->has('type') ? 'has-error' : '' }}}">
			<div class="col-md-12">
				<label class="control-label" for="type">{{{ Lang::get('l4cp-support::core.type') }}}</label>

				{{ Form::select('type', 
						array(
							'subject' => Lang::get('l4cp-support::core.subject'),
							'sender' => Lang::get('l4cp-support::core.sender'),
							'body' => Lang::get('l4cp-support::core.body')),
								Input::old('type', isset($spam) ? $spam->type : null), array('class' => 'form-control')) }} 	

				{{ $errors->first('type', '<span class="help-block">:message</span>') }}
			</div>
		</div>

		<div class="form-group {{{ $errors->has('content') ? 'has-error' : '' }}}">
			<div class="col-md-12">
				<label class="control-label" for="content">{{{ Lang::get('l4cp-support::core.content') }}}</label>
				<textarea required class="form-control"  name="content" id="content">{{{ Input::old('content', isset($spam) ? $spam->content : null) }}}</textarea>
				{{ $errors->first('content', '<span class="help-block">:message</span>') }}
			</div>
		</div>

		<div class="modal-footer">
			{{ Form::reset(Lang::get('button.cancel'), array('class' => 'btn btn-responsive btn-danger', 'onclick'=>"$('#site-modal').modal('hide')")); }} 
			{{ Form::reset(Lang::get('button.reset'), array('class' => 'btn btn-responsive btn-default')); }} 
			{{ Form::submit(Lang::get('button.save'), array('class' => 'btn btn-responsive btn-success')); }} 
		</div>
	{{ Form::close(); }}
	@endif
@stop