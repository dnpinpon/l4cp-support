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
		if(parent.$('#escalations').html()){
			var oTable = parent.$('#escalations').dataTable();
			oTable.fnReloadAjax();
		}
		closeModel();
	</script>
	@else

	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-general" data-toggle="tab">{{{ Lang::get('core.general') }}}</a></li>
		<li><a href="#tab-conditions" data-toggle="tab">{{{ Lang::get('l4cp-support::core.conditions') }}}</a></li>
		<li><a href="#tab-settings" data-toggle="tab">{{{ Lang::get('l4cp-support::core.actions') }}}</a></li>
		<li><a href="#tab-reply" data-toggle="tab">{{{ Lang::get('l4cp-support::core.add_reply') }}}</a></li>
	</ul>


	@if (isset($escalations))
		{{ Form::open(array('method' => 'put','url' => URL::to('admin/support/escalations/' . $escalations->id . '/edit'), 'class' => 'form-horizontal form-ajax', 'onsubmit' => "$('#reply').html($('#editor').html());")) }}
	@else
		{{ Form::open(array('class' => 'form-horizontal form-ajax', 'onsubmit' => "$('#reply').html($('#editor').html());")) }}
	@endif

		<div class="tab-content">
			<div class="tab-pane active" id="tab-general">
				<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="name">{{{ Lang::get('l4cp-support::core.escalation') }}} {{{ Lang::get('l4cp-support::core.name') }}}</label>
						<input required class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($escalations) ? $escalations->name : null) }}}" />
						{{ $errors->first('name', '<span class="help-block">:message</span>') }}
					</div>
				</div>


				<div class="form-group {{{ $errors->has('delay') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="delay">{{{ Lang::get('l4cp-support::core.delay') }}}<small>{{{ Lang::get('l4cp-support::core.delay_hint') }}}</small></label>
						<input class="form-control" type="text" name="delay" id="delay" value="{{{ Input::old('delay', isset($escalations) ? $escalations->delay : null) }}}" />
						{{ $errors->first('delay', '<span class="help-block">:message</span>') }}
					</div>
				</div>
			</div>


			<div class="tab-pane" id="tab-conditions">
				<div class="form-group {{{ $errors->has('statuses') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="statuses">{{{ Lang::get('l4cp-support::core.status') }}}</label>
						{{ Form::select('statuses[]', Support::getStatuses(), isset($escalations) ? $escalations->currentStatusesIds() : null,array('class' => 'form-control','multiple' => true)); }}
						{{ $errors->first('statuses', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('priorities') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="priorities">{{{ Lang::get('l4cp-support::core.priority') }}}</label>
						{{ Form::select('priorities[]', Support::$priorities, Input::old('priorities[]', isset($escalations) ? json_decode($escalations->priority) : null),array('class' => 'form-control','multiple' => true)); }}
						{{ $errors->first('priorities', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('departments') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="departments">{{{ Lang::get('l4cp-support::core.department') }}}</label>
						{{ Form::select('departments[]', Support::getDeps(), isset($escalations) ? $escalations->currentDepIds() : null,array('class' => 'form-control','multiple' => true)); }}
						{{ $errors->first('departments', '<span class="help-block">:message</span>') }}
					</div>
				</div>
			</div>

			<div class="tab-pane" id="tab-settings">
				<div class="form-group {{{ $errors->has('new_status') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="new_status">{{{ Lang::get('l4cp-support::core.new_status') }}}</label>
						{{ Form::select('new_status', array('' => 'No change') + Support::getStatuses(), Input::old('new_status', isset($escalations) ? $escalations->new_status : null),array('class' => 'form-control')); }}
						{{ $errors->first('new_status', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('new_priority') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="new_priority">{{{ Lang::get('l4cp-support::core.new_priority') }}}</label>
						{{ Form::select('new_priority', array('4' => 'No change') + Support::$priorities, Input::old('new_priority', isset($escalations) ? $escalations->new_priority : null),array('class' => 'form-control')); }}
						{{ $errors->first('new_priority', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('new_department') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="new_department">{{{ Lang::get('l4cp-support::core.new_department') }}}</label>
						{{ Form::select('new_department', array('' => 'No change') + Support::getDeps(), Input::old('new_department', isset($escalations) ? $escalations->new_department : null),array('class' => 'form-control')); }}
						{{ $errors->first('new_department', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('flags') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="flags">{{{ Lang::get('l4cp-support::core.flag') }}}<small> {{{ Lang::get('l4cp-support::core.flag_hint') }}} </small></label>
						<input data-multi="true" data-url="{{{URL::to('admin/users/listadmin')}}}" id="user-flags" class="form-control" name="flags" type="hidden" value="{{{Input::old('flags', isset($escalations) ? implode(',',$escalations->currentFlags()) : null) }}}" tabindex="-1">
						{{ $errors->first('flags', '<span class="help-block">:message</span>') }}
					</div>
				</div>
				<script type="text/javascript">
					loadUserSelect('#user-flags', 'Select users');
				</script>

				<div class="form-group {{{ $errors->has('notify_admins') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="col-md-4 control-label" for="notify_admins">{{{ Lang::get('l4cp-support::core.notify_admins') }}}</label>
						<div class="btn-group btn-toggle" data-toggle="buttons">
							<label class="btn btn-default {{(Input::old('notify_admins', isset($escalations) ? $escalations->notify_admins : null) ? 'active btn-primary' : null)}}">
								{{ Form::radio('notify_admins', '1', (Input::old('notify_admins', isset($escalations) ? $escalations->notify_admins : null) ? true : null)) }} Yes
							</label>
							<label class="btn btn-default {{(Input::old('notify_admins', isset($escalations) ? $escalations->notify_admins : null) ? null : 'active btn-primary')}}">
								{{ Form::radio('notify_admins', '0', (Input::old('notify_admins', isset($escalations) ? $escalations->notify_admins : null) ? null : true)) }} No
							</label>
						 </div>
						{{ $errors->first('notify_admins', '<span class="help-block">:message</span>') }}
					</div>
				</div>



			</div>


			<div class="tab-pane" id="tab-reply">
				<div class="form-group {{{ $errors->has('reply') ? 'has-error' : '' }}}">
					<div class="col-md-12">
@section('wysiywg-content')
{{{ Input::old('reply', isset($escalations) ? $escalations->reply : null) }}}
@stop
						@include(Theme::path('wysiwyg'))
						<input class="hide" type="text" name="reply" id="reply" value="{{{ Input::old('reply', isset($escalations) ? $escalations->reply : null) }}}" />
						{{ $errors->first('reply', '<span class="help-block">:message</span>') }}
					</div>
					<script type="text/javascript">
						initToolbarBootstrapBindings();  
						$('#editor').wysiwyg({ fileUploadError: showErrorAlert, hotKeys: {}} );
					</script>
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