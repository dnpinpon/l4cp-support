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
		if(parent.$('#tickets').html()){
			var oTable = parent.$('#tickets').dataTable();
			oTable.fnReloadAjax();
		}
		closeModel();
	</script>
	@else


	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-general" data-toggle="tab">{{{ Lang::get('core.content') }}}</a></li>
		<li><a href="#tab-user" data-toggle="tab">{{{ Lang::get('core.user') }}}</a></li>
		<li><a href="#tab-settings" data-toggle="tab">{{{ Lang::get('core.settings') }}}</a></li>
			<li><a href="#tab-notes" data-toggle="tab">{{{ Lang::get('core.notes') }}}</a></li>
		@if(isset($tickets))
			<li><a href="#tab-logs" data-toggle="tab">{{{ Lang::get('l4cp-support::core.logs') }}}</a></li>
		@endif
	</ul>



	@if (isset($tickets))
		{{ Form::open(array('method' => 'put','url' => URL::to('admin/support/' . $tickets->id . '/edit'), 'class' => 'form-horizontal form-ajax', 'onsubmit' => "$('#content').html($('#editor').html());")) }}
	@else
		{{ Form::open(array('class' => 'form-horizontal form-ajax', 'onsubmit' => "$('#content').html($('#editor').html());")) }}
	@endif

		<div class="tab-content">
			<div class="tab-pane active" id="tab-general">
				<div class="form-group {{{ $errors->has('title') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<input placeholder="{{{ Lang::get('l4cp-support::core.title') }}}" class="form-control" type="text" name="title" id="title" value="{{{ Input::old('title', isset($tickets) ? $tickets->title : null) }}}" />
						{{ $errors->first('title', '<span class="help-block">:message</span>') }}
					</div>
				</div>
				<div class="form-group {{{ $errors->has('content') ? 'has-error' : '' }}}">
					<div class="col-md-12">
@section('wysiywg-content')
{{ Input::old('content', isset($tickets) ? Filter::filter(nl2br(strip_tags($tickets->message, '*'))) : null) }}
@stop
						@include(Theme::path('wysiwyg'))
						<textarea class="hide" name="content" id="content">{{{ Input::old('content', isset($tickets) ? Filter::filter($tickets->message, '*') : null) }}}</textarea>
						{{ $errors->first('content', '<span class="help-block">:message</span>') }}
					</div>
					<script type="text/javascript">
						initToolbarBootstrapBindings();  
						$('#editor').wysiwyg({ fileUploadError: showErrorAlert, hotKeys: {}} );
					</script>
				</div>

				<div class="modal-footer">
					<div class="col-md-6">
						<div class="pull-left">
							{{ Form::reset(Lang::get('button.cancel'), array('class' => 'btn btn-responsive btn-danger', 'onclick'=>"$('#site-modal').modal('hide')")); }} 
							{{ Form::reset(Lang::get('button.reset'), array('class' => 'btn btn-responsive btn-default')); }} 
						</div>
					</div>
					<div class="col-md-6">
						<div class="pull-right">
							{{ Form::button(Lang::get('button.previous'), array('class' => 'btn btn-responsive btn-default', 'onclick'=>"prevTab('.nav-tabs')")); }} 
							{{ Form::button(Lang::get('button.next'), array('class' => 'btn btn-responsive btn-primary', 'onclick'=>"nextTab('.nav-tabs')")); }} 
						</div>
					</div>
				</div>
			</div>

			<div class="tab-pane" id="tab-user">
				<div style="height: 280px">
				<div class="form-group {{{ $errors->has('user_id') ? 'has-error' : '' }}}">
					<div class="col-md-12">
					<input data-url="{{{URL::to('admin/users/list')}}}" id="user-select" class="form-control" name="user_id" type="hidden" value="{{{ isset($tickets) ? $tickets->user_id : null }}}" tabindex="-1">
						{{ $errors->first('user_id', '<span class="help-block">:message</span>') }}
					</div>
				</div>
				<script type="text/javascript">
					loadUserSelect('#user-select', 'Select a user');
				</script>

				<hr/>
					<strong>Or send to e-mail</strong>

				<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<input placeholder="{{{ Lang::get('l4cp-support::core.name') }}}" class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($tickets) ? $tickets->name : null) }}}" />
						{{ $errors->first('name', '<span class="help-block">:message</span>') }}
					</div>
				</div>
				<div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<input placeholder="{{{ Lang::get('core.email') }}}" class="form-control" type="email" name="email" id="email" value="{{{ Input::old('email', isset($tickets) ? $tickets->email : null) }}}" />
						{{ $errors->first('email', '<span class="help-block">:message</span>') }}
					</div>
				</div>
</div>

				<div class="modal-footer">
					<div class="col-md-4">
						<div class="pull-left">
							{{ Form::reset(Lang::get('button.cancel'), array('class' => 'btn btn-responsive btn-danger', 'onclick'=>"$('#site-modal').modal('hide')")); }} 
							{{ Form::reset(Lang::get('button.reset'), array('class' => 'btn btn-responsive btn-default')); }} 
						</div>
					</div>
					<div class="col-md-8">
						<div class="pull-right">
							<label class=" control-label" for="notify">{{{ Lang::get('l4cp-support::core.notify_user') }}}</label>
							<div class="btn-group btn-toggle" data-toggle="buttons">
								<label class="btn btn-default {{ !isset($tickets) ? 'active btn-primary' : null }}">
									{{ Form::radio('notify', '1', !isset($tickets) ? true : false) }} Yes
								</label>
								<label class="btn btn-default {{ !isset($tickets) ? null : 'active btn-primary' }}">
									{{ Form::radio('notify', '0', !isset($tickets) ? false : true) }} No
								</label>
							 </div>


							{{ Form::button(Lang::get('button.previous'), array('class' => 'btn btn-responsive btn-default', 'onclick'=>"prevTab('.nav-tabs')")); }} 
							{{ Form::button(Lang::get('button.next'), array('class' => 'btn btn-responsive btn-primary', 'onclick'=>"nextTab('.nav-tabs')")); }} 
						</div>
					</div>
				</div>
			</div>

			<div class="tab-pane" id="tab-settings">
				<div class="form-group {{{ $errors->has('department_id') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="department_id">{{{ Lang::get('l4cp-support::core.department') }}}</label>
						{{ Form::select('department_id', Support::getDeps(), isset($tickets) ? $tickets->department_id : null,array('class' => 'form-control')); }}
						{{ $errors->first('department_id', '<span class="help-block">:message</span>') }}
					</div>
				</div>
				<div class="form-group {{{ $errors->has('status') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="status">{{{ Lang::get('l4cp-support::core.status') }}}</label>
						{{ Form::select('status', Support::getStatuses(), isset($tickets) ? $tickets->status : null,array('class' => 'form-control')); }}
						{{ $errors->first('status', '<span class="help-block">:message</span>') }}
					</div>
				</div>
				<div class="form-group {{{ $errors->has('priority') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="priority">{{{ Lang::get('l4cp-support::core.priority') }}}</label>
						{{ Form::select('priority', Support::$priorities, isset($tickets) ? $tickets->priority : null,array('class' => 'form-control')); }}
						{{ $errors->first('priority', '<span class="help-block">:message</span>') }}
					</div>
				</div>


				<div class="form-group {{{ $errors->has('flags') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="flags">{{{ Lang::get('l4cp-support::core.flag') }}}</label>
						<input data-multi="true" data-url="{{{URL::to('admin/users/listadmin')}}}" id="user-flags" class="form-control" name="flags" type="hidden" value="{{{Input::old('flags', isset($tickets) ? implode(',',$tickets->currentFlags()) : null) }}}" tabindex="-1">
						{{ $errors->first('flags', '<span class="help-block">:message</span>') }}
					</div>
				</div>
				<script type="text/javascript">
					loadUserSelect('#user-flags', 'Select users');
				</script>


				<div class="modal-footer">
					<div class="col-md-6">
						<div class="pull-left">
							{{ Form::reset(Lang::get('button.cancel'), array('class' => 'btn btn-responsive btn-danger', 'onclick'=>"$('#site-modal').modal('hide')")); }} 
							{{ Form::reset(Lang::get('button.reset'), array('class' => 'btn btn-responsive btn-default')); }} 
						</div>
					</div>
					<div class="col-md-6">
						<div class="pull-right">
							{{ Form::button(Lang::get('button.previous'), array('class' => 'btn btn-responsive btn-default', 'onclick'=>"prevTab('.nav-tabs')")); }} 
							{{ Form::button(Lang::get('button.next'), array('class' => 'btn btn-responsive btn-primary', 'onclick'=>"nextTab('.nav-tabs')")); }} 
							{{ Form::submit(Lang::get('button.save'), array('class' => 'btn btn-responsive btn-success')); }} 
						</div>
					</div>
				</div>
			</div>


			<div class="tab-pane" id="tab-notes">
				@if (isset($tickets))
					@include(Theme::path('admin/dt-loading'))

					<div id="usernotes-container" class="dt-wrapper">
						<table id="usernotes" class=" table table-striped table-hover table-bordered">
							<thead>
								<tr>
									<th></th>
									<th class="col-md-6">{{{ Lang::get('admin/users/table.details') }}}</th>
									<th class="col-md-2">{{{ Lang::get('admin/users/table.created_at') }}}</th>
									<th class="col-md-2">{{{ Lang::get('admin/users/table.updated_at') }}}</th>
									<th class="col-md-2">{{{ Lang::get('admin/users/table.created_by') }}}</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<hr/>

					<textarea class="form-control" name="notes[]" placeholder="{{{Lang::get('core.new_note')}}}"></textarea>
				@else
					<textarea class="form-control" name="notes" rows="14" placeholder="{{{Lang::get('core.new_note')}}}"></textarea>
				@endif


				<div class="modal-footer">
					<div class="col-md-6">
						<div class="pull-left">
							{{ Form::reset(Lang::get('button.cancel'), array('class' => 'btn btn-responsive btn-danger', 'onclick'=>"$('#site-modal').modal('hide')")); }} 
							{{ Form::reset(Lang::get('button.reset'), array('class' => 'btn btn-responsive btn-default')); }} 
						</div>
					</div>
					<div class="col-md-6">
						<div class="pull-right">
							{{ Form::button(Lang::get('button.previous'), array('class' => 'btn btn-responsive btn-default', 'onclick'=>"prevTab('.nav-tabs')")); }} 
							{{ Form::submit(Lang::get('button.save'), array('class' => 'btn btn-responsive btn-success')); }} 
						</div>
					</div>
				</div>
			</div>

		@if (isset($tickets))
			<div class="tab-pane" id="tab-logs">
				@include(Theme::path('admin/dt-loading'))

				<div id="activitylog-container" class="dt-wrapper">
					<table id="activitylog" class="table-responsive table table-striped table-hover table-bordered">
						<thead>
							<tr>
								<th></th>
								<th>{{{ Lang::get('l4cp-support::core.action') }}}</th>
								<th>{{{ Lang::get('admin/users/table.created_at') }}}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			
			<script type="text/javascript">
				dtLoad('#activitylog', "{{URL::to('admin/support/' . $tickets->id . '/activity') }}", 'td:eq(2), th:eq(2)', 'td:eq(1), th:eq(1)', '', 'false', 'true');
				dtLoad('#usernotes', "{{URL::to('admin/support/' . $tickets->id . '/notes') }}", 'td:eq(2), th:eq(2)', 'td:eq(1), th:eq(1)', '', 'false', 'true');
			</script>
		@endif

		</div>
	{{ Form::close(); }}
	@endif

@stop