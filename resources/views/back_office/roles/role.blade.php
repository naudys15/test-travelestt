@extends('back_office.layouts.template')

@section('content')
	{{ Form::open(array('url' => $data['url'], 'id' => 'form_site', 'class' => 'form', 'method' => $data['method'] )) }}
        <div class="row" id="setLanguage" onclick="setLanguage('spanish')">
            <!-- informacion -->
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-body">
                    	<h4 class="mar-btm">{{ __('language.role') }}</h4>

                    	<div class="form-group">
                            <div class="row">
                                <label class="col-6 control-label" for="name">{{ __('language.name') }}</label>
                                <div class="col-6">
                                    {!! Form::text('name', null, [ 'id' => 'name', 'class' => 'form-control', 'placeholder' => Lang::get('language.name') ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-6 control-label" for="description">{{ __('language.description') }}</label>
                                <div class="col-6">
                                    {!! Form::text('description', null, [ 'id' => 'description', 'class' => 'form-control', 'placeholder' => Lang::get('language.description') ]) !!}
                                </div>
                            </div>
                        </div>
                	<div class="well mar-top"></div>
                	</div>
                	<div class="panel-footer">
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