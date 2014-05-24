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
		$('#site-modal').modal('hide');
	</script>
@else




	@if (isset($tickets))
		{{ Form::open(array('method' => 'put','url' => URL::to('admin/support/' . $tickets->id . '/edit'), 'class' => 'form-horizontal form-ajax', 'onsubmit' => "$('#content').html($('#editor').html());")) }}
	@else
		{{ Form::open(array('class' => 'form-horizontal form-ajax', 'onsubmit' => "$('#content').html($('#editor').html());")) }}
	@endif

				<div class="form-group {{{ $errors->has('content') ? 'has-error' : '' }}}">
					<div class="col-md-12">
@section('wysiywg-content')
{{ Input::old('content', isset($tickets) ? Filter::filter($tickets->message, '*') : null) }}
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
							<div class="form-group {{{ $errors->has('status') ? 'has-error' : '' }}}">
								<label class="control-label col-sm-3" for="status">{{{ Lang::get('l4cp-support::core.status') }}}</label>
								<div class="col-sm-9">
								{{ Form::select('status', Support::getStatuses(), isset($replyoption) ? $replyoption->id : null,array('class' => 'form-control')); }}
								{{ $errors->first('status', '<span class="help-block">:message</span>') }}
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="pull-right">
							{{ Form::reset(Lang::get('button.cancel'), array('class' => 'btn btn-responsive btn-danger', 'onclick'=>"$('#site-modal').modal('hide')")); }} 
							{{ Form::reset(Lang::get('button.reset'), array('class' => 'btn btn-responsive btn-default')); }} 

							{{ Form::submit(Lang::get('l4cp-support::core.mark').' '.$button->title. ' &amp; ' . Lang::get('button.save'), array('class' => 'btn btn-responsive btn-primary', 'id'=>'default_button')); }} 

							{{ Form::submit(Lang::get('button.save'), array('class' => 'btn btn-responsive btn-success')); }} 
						</div>
					</div>
				</div>
	
	{{ Form::close(); }}
	<script type="text/javascript">
		$('#default_button').on('click', function(){
			$('select[name=status]').val('{{ $button->id }}');
			return true;
		});
	</script>

	@endif


@stop