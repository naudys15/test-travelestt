@extends('back_office.layouts.template')

@section('content')
	{{ Form::open(array('url' => $data['url'], 'id' => 'form_location', 'class' => 'form', 'method' => $data['method'], 'files' => true )) }}
        <div class="row" id="setLanguage" onclick="setLanguage()">
            <!-- informacion -->
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-body">

                        <div class="col-12">
                            <h4 class="mar-btm">{{ __('language.country') }}</h4>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <label class="col-12 control-label" for="state">ISO</label>
                                <div class="col-12">
                                    {!! Form::text('iso', null, [ 'id' => 'iso', 'class' => 'form-control', 'placeholder' => 'ISO' ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <label class="col-12 control-label" for="city">{{ __('language.name') }}</label>
                                <div class="col-12">
                                    {!! Form::text('name', null, [ 'id' => 'name', 'class' => 'form-control', 'placeholder' => Lang::get('language.name') ]) !!}
                                </div>
                            </div>
                        </div>

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
