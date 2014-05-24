@extends(Theme::path('admin/layouts/modal'))

@section('title')
	{{{ $title }}}
@stop

@section('styles')
	<style type="text/css"> 
		#editor {
			height: 200px;
			overflow: auto;
		}
	</style>
@stop

@section('content')
	@if ($message = Session::get('success'))
	<script type="text/javascript">
		if(parent.$('#replies').html()){
			var oTable = parent.$('#replies').dataTable();
			oTable.fnReloadAjax();
		}
		closeModel();
	</script>
	@else

	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-general" data-toggle="tab">{{{ Lang::get('core.general') }}}</a></li>
		<li><a href="#tab-settings" data-toggle="tab">{{{ Lang::get('core.settings') }}}</a></li>
	</ul>

	@if (isset($autoreply))
		{{ Form::open(array('method' => 'put','url' => URL::to('admin/support/autoreplies/' . $autoreply->id . '/edit'), 'class' => 'form-horizontal form-ajax', 'onsubmit' => "$('#content').html($('#editor').html());")) }}
	@else
		{{ Form::open(array('class' => 'form-horizontal form-ajax', 'onsubmit' => "$('#content').html($('#editor').html());")) }}
	@endif

		<div class="tab-content">
			<div class="tab-pane active" id="tab-general">
				<div class="form-group {{{ $errors->has('title') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="title">{{{ Lang::get('l4cp-support::core.subject') }}}</label>
						<input required class="form-control" type="text" name="title" id="title" value="{{{ Input::old('title', isset($autoreply) ? $autoreply->title : null) }}}" />
						{{ $errors->first('title', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('content') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label">{{{ Lang::get('l4cp-support::core.content') }}}</label>
@section('wysiywg-content')
{{ Input::old('content', isset($autoreply) ? $autoreply->content : null) }}
@stop
						@include(Theme::path('wysiwyg'))
						<textarea class="hide" name="content" id="content">{{{ Input::old('content', isset($autoreply) ? $autoreply->content : null) }}}</textarea>
						{{ $errors->first('content', '<span class="help-block">:message</span>') }}
					</div>
					<script type="text/javascript">
						initToolbarBootstrapBindings();  
						$('#editor').wysiwyg({ fileUploadError: showErrorAlert, hotKeys: {}} );
					</script>
				</div>

			</div>
			<div class="tab-pane" id="tab-settings">
				<div class="form-group {{{ $errors->has('roles') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="roles">{{{ Lang::get('l4cp-support::core.roles') }}}</label>
						{{ Form::select('roles[]', $roles, isset($autoreply) ? $autoreply->currentRoles() : null,array('class' => 'form-control','multiple' => true, 'required'=>true)); }}
						{{ $errors->first('roles', '<span class="help-block">:message</span>') }}
					</div>
				</div>
				
				
				<div class="form-group {{{ $errors->has('actions') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="actions">{{{ Lang::get('l4cp-support::core.actions') }}}</label>
						{{ Form::select('actions[]', $actions, isset($autoreply) ? $autoreply->currentActions() : null,array('class' => 'form-control','multiple' => true, 'required'=>true)); }}
						{{ $errors->first('actions', '<span class="help-block">:message</span>') }}
					</div>
				</div>


				<div class="form-group {{{ $errors->has('departments') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="departments">{{{ Lang::get('l4cp-support::core.department') }}}</label>
						{{ Form::select('departments[]', $deps, isset($autoreply) ? $autoreply->currentDepIds() : null,array('class' => 'form-control','multiple' => true, 'required'=>true)); }}
						{{ $errors->first('departments', '<span class="help-block">:message</span>') }}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				{{ Form::reset(Lang::get('button.cancel'), array('class' => 'btn btn-responsive btn-danger', 'onclick'=>"$('#site-modal').modal('hide')")); }} 
				{{ Form::reset(Lang::get('button.reset'), array('class' => 'btn btn-responsive btn-default')); }} 
				{{ Form::submit(Lang::get('button.save'), array('class' => 'btn btn-responsive btn-success')); }} 
			</div>
		</div>
	{{ Form::close(); }}
	@endif
@stop