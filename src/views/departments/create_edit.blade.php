@extends(Theme::path('admin/layouts/modal'))

@section('title')
	{{{ $title }}}
@stop

@section('content')
	@if ($message = Session::get('success'))
	<script type="text/javascript">
		if(parent.$('#departments').html()){
			var oTable = parent.$('#departments').dataTable();
			oTable.fnReloadAjax();
		}
		closeModel();
	</script>
	@else


	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-general" data-toggle="tab">{{{ Lang::get('core.general') }}}</a></li>
		<li><a href="#tab-settings" data-toggle="tab">{{{ Lang::get('core.settings') }}}</a></li>
		<li><a href="#tab-pop3" data-toggle="tab">{{{ Lang::get('core.email') }}}</a></li>
	</ul>



	@if (isset($department))
		{{ Form::open(array('method' => 'put','url' => URL::to('admin/support/departments/' . $department->id . '/edit'), 'class' => 'form-horizontal form-ajax')) }}
	@else
		{{ Form::open(array('class' => 'form-horizontal form-ajax')) }}
	@endif

		<div class="tab-content">
			<div class="tab-pane active" id="tab-general">
				<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="name">{{{ Lang::get('l4cp-support::core.name') }}}</label>
						<input required class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($department) ? $department->name : null) }}}" />
						{{ $errors->first('name', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="description">{{{ Lang::get('l4cp-support::core.description') }}}</label>
						<input class="form-control" type="text" name="description" id="description" value="{{{ Input::old('description', isset($department) ? $department->description : null) }}}" />
						{{ $errors->first('description', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('sort') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="sort">{{{ Lang::get('l4cp-support::core.sort') }}}</label>
						<input class="form-control" type="text" name="sort" id="sort" value="{{{ Input::old('sort', isset($department) ? $department->sort : null) }}}" />
						{{ $errors->first('sort', '<span class="help-block">:message</span>') }}
					</div>
				</div>
			</div>


			<div class="tab-pane" id="tab-settings">
				<div class="form-group {{{ $errors->has('flags') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="flags">{{{ Lang::get('l4cp-support::core.flag') }}}</label>
						{{ Form::select('flags[]', $admins, Input::old('flags[]', isset($department) ? $department->currentFlags() : null),array('class' => 'form-control','multiple' => true, 'required'=>true)); }}
						{{ $errors->first('flags', '<span class="help-block">:message</span>') }}
					</div>
				</div>


				<div class="form-group {{{ $errors->has('clients_only') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="col-md-4 control-label" for="clients_only">{{{ Lang::get('l4cp-support::core.clients_only') }}}</label>
						<div class="btn-group btn-toggle" data-toggle="buttons">
							<label class="btn btn-default {{(Input::old('clients_only', isset($department) ? $department->clients_only : null) ? 'active btn-primary' : null)}}">
								{{ Form::radio('clients_only', '1', (Input::old('clients_only', isset($department) ? $department->clients_only : null) ? true : null)) }} Yes
							</label>
							<label class="btn btn-default {{(Input::old('clients_only', isset($department) ? $department->clients_only : null) ? null : 'active btn-primary')}}">
								{{ Form::radio('clients_only', '0', (Input::old('clients_only', isset($department) ? $department->clients_only : null) ? null : true)) }} No
							</label>
						 </div>
						{{ $errors->first('clients_only', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('auto_respond') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="col-md-4 control-label" for="auto_respond">{{{ Lang::get('l4cp-support::core.auto_respond') }}}</label>
						<div class="btn-group btn-toggle" data-toggle="buttons">
							<label class="btn btn-default {{(Input::old('auto_respond', isset($department) ? $department->auto_respond : null) ? 'active btn-primary' : null)}}">
								{{ Form::radio('auto_respond', '1', (Input::old('auto_respond', isset($department) ? $department->auto_respond : null) ? true : null)) }} Yes
							</label>
							<label class="btn btn-default {{(Input::old('auto_respond', isset($department) ? $department->auto_respond : null) ? null : 'active btn-primary')}}">
								{{ Form::radio('auto_respond', '0', (Input::old('auto_respond', isset($department) ? $department->auto_respond : null) ? null : true)) }} No
							</label>
						 </div>
						{{ $errors->first('auto_respond', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('hidden') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="col-md-4 control-label" for="hidden">{{{ Lang::get('l4cp-support::core.hidden') }}}</label>
						<div class="btn-group btn-toggle" data-toggle="buttons">
							<label class="btn btn-default {{(Input::old('hidden', isset($department) ? $department->hidden : null) ? 'active btn-primary' : null)}}">
								{{ Form::radio('hidden', '1', (Input::old('hidden', isset($department) ? $department->hidden : null) ? true : null)) }} Yes
							</label>
							<label class="btn btn-default {{(Input::old('hidden', isset($department) ? $department->hidden : null) ? null : 'active btn-primary')}}">
								{{ Form::radio('hidden', '0', (Input::old('hidden', isset($department) ? $department->hidden : null) ? null : true)) }} No
							</label>
						 </div>
						{{ $errors->first('hidden', '<span class="help-block">:message</span>') }}
					</div>
				</div>
			</div>


			<div class="tab-pane" id="tab-pop3">
				<div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<input placeholder="{{{ Lang::get('l4cp-support::core.email') }}}" class="form-control" type="text" name="email" id="email" value="{{{ Input::old('email', isset($department) ? $department->email : null) }}}" />
						{{ $errors->first('email', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('pop_host') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<input placeholder="{{{ Lang::get('l4cp-support::core.pop_host') }}}" class="form-control" type="text" name="pop_host" id="pop_host" value="{{{ Input::old('pop_host', isset($department) ? $department->pop_host : null) }}}" />
						{{ $errors->first('pop_host', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('pop_port') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<input placeholder="{{{ Lang::get('l4cp-support::core.pop_port') }}}" class="form-control" type="text" name="pop_port" id="pop_port" value="{{{ Input::old('pop_port', isset($department) ? $department->pop_port : null) }}}" />
						{{ $errors->first('pop_port', '<span class="help-block">:message</span>') }}
					</div>
				</div>


				<div class="form-group {{{ $errors->has('pop_user') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<input placeholder="{{{ Lang::get('l4cp-support::core.pop_user') }}}" class="form-control" type="text" name="pop_user" id="pop_user" value="{{{ Input::old('pop_user', isset($department) ? $department->pop_user : null) }}}" />
						{{ $errors->first('pop_user', '<span class="help-block">:message</span>') }}
					</div>
				</div>


				<div class="form-group {{{ $errors->has('pop_pass') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<input placeholder="{{{ Lang::get('l4cp-support::core.pop_pass') }}}" class="form-control" type="text" name="pop_pass" id="pop_pass" value="{{{ Input::old('pop_pass', isset($department) ? $department->pop_pass : null) }}}" />
						{{ $errors->first('pop_pass', '<span class="help-block">:message</span>') }}
					</div>
				</div>
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