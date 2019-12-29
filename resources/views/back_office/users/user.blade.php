@extends('back_office.layouts.template')

@section('content')
	{{ Form::open(array('url' => $data['url'], 'id' => 'form_site', 'class' => 'form', 'method' => $data['method'], 'files' => true)) }}
        <div class="row" id="setLanguage" onclick="setLanguage('spanish')">
            <!-- informacion -->
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-body">
                    	<h4 class="mar-btm">{{ $data['edit']? __('language.update_user') : __('language.create_user') }}</h4>

    					<div class="col-lg-6">
	                    	<div class="form-group">
	                            <div class="row">
	                                <label class="col-6 control-label" for="firstname">{{ __('language.firstname') }}</label>
	                                <div class="col-6">
	                                    {!! Form::text('firstname', null, [ 'id' => 'firstname', 'class' => 'form-control', 'placeholder' => Lang::get('language.firstname') ]) !!}
	                                </div>
	                            </div>
	                        </div>
    					</div>
    					<div class="col-lg-6">
	                        <div class="form-group">
	                            <div class="row">
	                                <label class="col-6 control-label" for="lastname">{{ __('language.lastname') }}</label>
	                                <div class="col-6">
	                                    {!! Form::text('lastname', null, [ 'id' => 'lastname', 'class' => 'form-control', 'placeholder' => Lang::get('language.lastname') ]) !!}
	                                </div>
	                            </div>
	                        </div>
	                    </div>
    					<div class="col-lg-6">
	                        <div class="form-group">
	                            <div class="row">
	                                <label class="col-6 control-label" for="email">{{ __('language.email') }}</label>
	                                <div class="col-6">
	                                    {!! Form::email('email', null, [ 'id' => 'email', 'class' => 'form-control', 'placeholder' => Lang::get('language.email') ]) !!}
	                                </div>
	                            </div>
	                        </div>
                        </div>
    					<div class="col-lg-6">
	                        <div class="form-group">
		                        <div class="row">
		                            <label class="col-12 control-label">{{ __('language.phonenumber') }}</label>
		                            <div class="col-12">
		                                    {!! Form::text('phonenumber', null, [ 'id' => 'phonenumber', 'class' => 'form-control', 'placeholder' => Lang::get('language.phonenumber') ]) !!}
		                            </div>
		                        </div>
		                    </div>
    					</div>
    					<div class="col-lg-6">
		                    <div class="form-group">
		                        <div class="row">
		                            <label class="col-6 control-label">{{ __('language.province') }}</label>
		                            <div class="col-6">
		                                {!! Form::select('state', [], null, [ 'id' => 'state', 'class' => 'form-control', 'placeholder' => Lang::get('language.select_state') ]) !!}
		                            </div>
		                        </div>
		                    </div>
		                </div>
    					<div class="col-lg-6">
		                    <div class="form-group">
		                        <div class="row">
		                            <label class="col-6 control-label" for="city">{{ __('language.city') }}</label>
		                            <div class="col-6">
		                                {!! Form::select('city', [], null, [ 'id' => 'city', 'class' => 'form-control', 'placeholder' => Lang::get('language.select_city') ]) !!}
		                            </div>
		                        </div>
		                    </div>
		                </div>
    					<div class="col-lg-6">
		                    <div class="form-group">
	    				   		<div class="row">
			                        <label class="col-12 control-label">{{__('language.password') }}</label>
		                            <div class="col-12">
		                                {!! Form::text('password', null, [ 'id' => 'password', 'class' => 'form-control', 'placeholder' => Lang::get('language.password') ]) !!}
		                            </div>
			                  	</div>
		                  	</div>
		                </div>
    					<div class="col-lg-6">
	                    	<div class="form-group">
		                        <div class="row">
		                            <label class="col-12 control-label">{{ __('language.confirm_password') }}</label>
		                            <div class="col-12">
		                                    {!! Form::text('password_confirmation', null, [ 'id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => Lang::get('language.confirm_password') ]) !!}
		                            </div>
		                        </div>
	                    	</div>
	                	</div>
	                	<div class="col-lg-6">
		                    <div class="form-group">
		                        <div class="row">
		                            <label class="col-6 control-label" for="rol">{{ __('language.rol') }}</label>
		                            <div class="col-6">
		                                {!! Form::select('rol', [], null, [ 'id' => 'rol', 'class' => 'form-control', 'placeholder' => Lang::get('language.select_rol') ]) !!}
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-lg-12 text-center">
		                	<h4 class="mar-btm">{{ __('language.image_user') }}</h4>
		                	<div id="dropzoneFiles" action="#" class="">
                                <div class="dz-default dz-message">
                                    <div class="dz-icon">
                                    <i class="demo-pli-upload-to-cloud icon-5x"></i>
                                    </div>
                                    <div>
                                    <span class="dz-text">{{ __('language.drop_file_to_upload') }}</span>
                                    <p class="text-sm text-muted">{{ __('language.or_click_to_pick_manually') }}</p>
                                    </div>
                                </div>
                            </div>

		                </div>
		            </div>
                	<div class="panel-footer">
                    	<div class="well mar-top"></div>
	                    <div class="text-right mar-top">
	                        <button class="btn btn-mint" type="submit" value="Guardar" id="submit_btn">{{ __('language.save') }}</button>
	                    </div>
                	</div>
                </div>
            </div>
        </div>
        <div id="loading_modal" class="modal fade in" tabindex="-1" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content modal-backdrop">
                    <div class="modal-body">
                        <div class="d-flex justify-content-center">
                            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	{{ Form::close() }}
@endsection

@section('script')
@endsection