@extends(Theme::path('admin/layouts/modal'))

@section('title')
	{{{ $title }}}
@stop

@section('content')
	<link rel="stylesheet" href="{{{ asset('assets/css/bootstrap-colorselector.css') }}}"/>
	<script src="{{{ asset('assets/js/bootstrap-colorselector.js') }}}"></script>


	@if ($message = Session::get('success'))
	<script type="text/javascript">
		if(parent.$('#statuses').html()){
			var oTable = parent.$('#statuses').dataTable();
			oTable.fnReloadAjax();
		}
		closeModel();
	</script>
	@else

	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-general" data-toggle="tab">{{{ Lang::get('core.general') }}}</a></li>
		<li><a href="#tab-settings" data-toggle="tab">{{{ Lang::get('core.settings') }}}</a></li>
	</ul>


	@if (isset($statuses))
		{{ Form::open(array('method' => 'put','url' => URL::to('admin/support/statuses/' . $statuses->id . '/edit'), 'class' => 'form-horizontal form-ajax')) }}
	@else
		{{ Form::open(array('class' => 'form-horizontal form-ajax')) }}
	@endif


		<div class="tab-content">
			<div class="tab-pane active" id="tab-general">
				<div class="form-group {{{ $errors->has('title') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="title">{{{ Lang::get('l4cp-support::core.title') }}}</label>
						<input required class="form-control" type="text" name="title" id="title" value="{{{ Input::old('title', isset($statuses) ? $statuses->title : null) }}}" />
						{{ $errors->first('title', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('color') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="color">{{{ Lang::get('l4cp-support::core.color') }}}</label>
						<select class="colorselector" name="color">
							  <option value="" data-color=""></option>
							  <option value="#A0522D" data-color="#A0522D" {{{ Input::old('color', isset($statuses) && $statuses->color == "#A0522D" ? "selected" : null) }}}>sienna</option>
							  <option value="#CD5C5C" data-color="#CD5C5C" {{{ Input::old('color', isset($statuses) && $statuses->color == "#CD5C5C" ? "selected" : null) }}}>indianred</option>
							  <option value="#FF4500" data-color="#FF4500" {{{ Input::old('color', isset($statuses) && $statuses->color == "#FF4500" ? "selected" : null) }}}>orangered</option>
							  <option value="#008B8B" data-color="#008B8B" {{{ Input::old('color', isset($statuses) && $statuses->color == "#008B8B" ? "selected" : null) }}}>darkcyan</option>
							  <option value="#B8860B" data-color="#B8860B" {{{ Input::old('color', isset($statuses) && $statuses->color == "#B8860B" ? "selected" : null) }}}>darkgoldenrod</option>
							  <option value="#32CD32" data-color="#32CD32" {{{ Input::old('color', isset($statuses) && $statuses->color == "#32CD32" ? "selected" : null) }}}>limegreen</option>
							  <option value="#FFD700" data-color="#FFD700" {{{ Input::old('color', isset($statuses) && $statuses->color == "#FFD700" ? "selected" : null) }}}>gold</option>
							  <option value="#48D1CC" data-color="#48D1CC" {{{ Input::old('color', isset($statuses) && $statuses->color == "#48D1CC" ? "selected" : null) }}}>mediumturquoise</option>
							  <option value="#87CEEB" data-color="#87CEEB" {{{ Input::old('color', isset($statuses) && $statuses->color == "#87CEEB" ? "selected" : null) }}}>skyblue</option>
							  <option value="#FF69B4" data-color="#FF69B4" {{{ Input::old('color', isset($statuses) && $statuses->color == "#FF69B4" ? "selected" : null) }}}>hotpink</option>
							  <option value="#87CEFA" data-color="#87CEFA" {{{ Input::old('color', isset($statuses) && $statuses->color == "#87CEFA" ? "selected" : null) }}}>lightskyblue</option>
							  <option value="#6495ED" data-color="#6495ED" {{{ Input::old('color', isset($statuses) && $statuses->color == "#6495ED" ? "selected" : null) }}}>cornflowerblue</option>
							  <option value="#DC143C" data-color="#DC143C" {{{ Input::old('color', isset($statuses) && $statuses->color == "#DC143C" ? "selected" : null) }}}>crimson</option>
							  <option value="#FF8C00" data-color="#FF8C00" {{{ Input::old('color', isset($statuses) && $statuses->color == "#FF8C00" ? "selected" : null) }}}>darkorange</option>
							  <option value="#C71585" data-color="#C71585" {{{ Input::old('color', isset($statuses) && $statuses->color == "#C71585" ? "selected" : null) }}}>mediumvioletred</option>
							  <option value="#000000" data-color="#000000" {{{ Input::old('color', isset($statuses) && $statuses->color == "#000000" ? "selected" : null) }}}>black</option>
						</select>


						{{ $errors->first('title', '<span class="help-block">:message</span>') }}
					</div>
					<script type="text/javascript">
						$('.colorselector').colorselector();
					</script>
				</div>

				<div class="form-group {{{ $errors->has('sort') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="control-label" for="sort">{{{ Lang::get('l4cp-support::core.sort') }}}</label>
						<input class="form-control" type="text" name="sort" id="sort" value="{{{ Input::old('sort', isset($statuses) ? $statuses->sort : null) }}}" />
						{{ $errors->first('sort', '<span class="help-block">:message</span>') }}
					</div>
				</div>
			</div>
			<div class="tab-pane" id="tab-settings">
				<div class="form-group {{{ $errors->has('show_active') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="col-md-4 control-label" for="show_active">{{{ Lang::get('l4cp-support::core.show_active') }}}</label>
						<div class="btn-group btn-toggle" data-toggle="buttons">
							<label class="btn btn-default {{(Input::old('show_active', isset($statuses) ? $statuses->show_active : null) ? 'active btn-primary' : null)}}">
								{{ Form::radio('show_active', '1', (Input::old('show_active', isset($statuses) ? $statuses->show_active : null) ? true : null)) }} Yes
							</label>
							<label class="btn btn-default {{(Input::old('show_active', isset($statuses) ? $statuses->show_active : null) ? null : 'active btn-primary')}}">
								{{ Form::radio('show_active', '0', (Input::old('show_active', isset($statuses) ? $statuses->show_active : null) ? null : true)) }} No
							</label>
						 </div>
						{{ $errors->first('show_active', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('default_flag') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="col-md-4 control-label" for="default_flag">{{{ Lang::get('l4cp-support::core.default_flag') }}}</label>
						<div class="btn-group btn-toggle" data-toggle="buttons">
							<label class="btn btn-default {{(Input::old('default_flag', isset($statuses) ? $statuses->default_flag : null) ? 'active btn-primary' : null)}}">
								{{ Form::radio('default_flag', '1', (Input::old('default_flag', isset($statuses) ? $statuses->default_flag : null) ? true : null)) }} Yes
							</label>
							<label class="btn btn-default {{(Input::old('default_flag', isset($statuses) ? $statuses->default_flag : null) ? null : 'active btn-primary')}}">
								{{ Form::radio('default_flag', '0', (Input::old('default_flag', isset($statuses) ? $statuses->default_flag : null) ? null : true)) }} No
							</label>
						 </div>
						{{ $errors->first('default_flag', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('default_button') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="col-md-4 control-label" for="default_button">{{{ Lang::get('l4cp-support::core.default_button') }}}</label>
						<div class="btn-group btn-toggle" data-toggle="buttons">
							<label class="btn btn-default {{(Input::old('default_button', isset($statuses) ? $statuses->default_button : null) ? 'active btn-primary' : null)}}">
								{{ Form::radio('default_button', '1', (Input::old('default_button', isset($statuses) ? $statuses->default_button : null) ? true : null)) }} Yes
							</label>
							<label class="btn btn-default {{(Input::old('default_button', isset($statuses) ? $statuses->default_button : null) ? null : 'active btn-primary')}}">
								{{ Form::radio('default_button', '0', (Input::old('default_button', isset($statuses) ? $statuses->default_button : null) ? null : true)) }} No
							</label>
						 </div>
						{{ $errors->first('default_button', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('default_category') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="col-md-4 control-label" for="default_category">{{{ Lang::get('l4cp-support::core.default_category') }}}</label>
						<div class="btn-group btn-toggle" data-toggle="buttons">
							<label class="btn btn-default {{(Input::old('default_category', isset($statuses) ? $statuses->default_category : null) ? 'active btn-primary' : null)}}">
								{{ Form::radio('default_category', '1', (Input::old('default_category', isset($statuses) ? $statuses->default_category : null) ? true : null)) }} Yes
							</label>
							<label class="btn btn-default {{(Input::old('default_category', isset($statuses) ? $statuses->default_category : null) ? null : 'active btn-primary')}}">
								{{ Form::radio('default_category', '0', (Input::old('default_category', isset($statuses) ? $statuses->default_category : null) ? null : true)) }} No
							</label>
						 </div>
						{{ $errors->first('default_category', '<span class="help-block">:message</span>') }}
					</div>
				</div>
				<div class="form-group {{{ $errors->has('close_status') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="col-md-4 control-label" for="close_status">{{{ Lang::get('l4cp-support::core.close_status') }}}</label>
						<div class="btn-group btn-toggle" data-toggle="buttons">
							<label class="btn btn-default {{(Input::old('close_status', isset($statuses) ? $statuses->close_status : null) ? 'active btn-primary' : null)}}">
								{{ Form::radio('close_status', '1', (Input::old('close_status', isset($statuses) ? $statuses->close_status : null) ? true : null)) }} Yes
							</label>
							<label class="btn btn-default {{(Input::old('close_status', isset($statuses) ? $statuses->close_status : null) ? null : 'active btn-primary')}}">
								{{ Form::radio('close_status', '0', (Input::old('close_status', isset($statuses) ? $statuses->close_status : null) ? null : true)) }} No
							</label>
						 </div>
						{{ $errors->first('close_status', '<span class="help-block">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('auto_close') ? 'has-error' : '' }}}">
					<div class="col-md-12">
						<label class="col-md-4 control-label" for="auto_close">{{{ Lang::get('l4cp-support::core.auto_close') }}}</label>
						<div class="btn-group btn-toggle" data-toggle="buttons">
							<label class="btn btn-default {{(Input::old('auto_close', isset($statuses) ? $statuses->auto_close : null) ? 'active btn-primary' : null)}}">
								{{ Form::radio('auto_close', '1', (Input::old('auto_close', isset($statuses) ? $statuses->auto_close : null) ? true : null)) }} Yes
							</label>
							<label class="btn btn-default {{(Input::old('auto_close', isset($statuses) ? $statuses->auto_close : null) ? null : 'active btn-primary')}}">
								{{ Form::radio('auto_close', '0', (Input::old('auto_close', isset($statuses) ? $statuses->auto_close : null) ? null : true)) }} No
							</label>
						 </div>
						{{ $errors->first('auto_close', '<span class="help-block">:message</span>') }}
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