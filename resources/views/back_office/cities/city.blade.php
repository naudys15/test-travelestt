@extends('back_office.layouts.template')

@section('content')
	{{ Form::open(array('url' => $data['url'], 'id' => 'form_location', 'class' => 'form', 'method' => $data['method'], 'files' => true )) }}
        <div class="row" id="setLanguage" onclick="setLanguage()">
            <!-- informacion -->
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-body">

                        <div class="col-12">
                            <h4 class="mar-btm">{{ __('language.city') }}</h4>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <label class="col-12 control-label" for="slug">Slug</label>
                                <div class="col-12">
                                    {!! Form::text('slug', null, [ 'id' => 'slug', 'class' => 'form-control', 'placeholder' => 'Slug' ]) !!}
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

                        <div class="form-group">
                            <div class="">
                                <label class="col-12 control-label" for="name">{{ __('language.latitude') }}</label>
                                <div class="col-12">
                                    {!! Form::number('latitude', null, [ 'id' => 'latitude', 'class' => 'form-control', 'step' => '0.000001', 'placeholder' => Lang::get('language.latitude') ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <label class="col-12 control-label" for="longitude">{{ __('language.longitude') }}</label>
                                <div class="col-12">
                                    {!! Form::number('longitude', null, [ 'id' => 'longitude', 'class' => 'form-control', 'step' => '0.000001', 'placeholder' => Lang::get('language.longitude') ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <label class="col-12 control-label" for="altitude">{{ __('language.altitude') }}</label>
                                <div class="col-12">
                                    {!! Form::number('altitude', null, [ 'id' => 'altitude', 'class' => 'form-control', 'step' => '0.0001', 'placeholder' => Lang::get('language.altitude') ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <label class="col-12 control-label" for="country">{{ __('language.country') }}</label>
                                <div class="col-12">
                                    {!! Form::select('country', [], null, [ 'id' => 'country', 'class' => 'form-control', 'placeholder' => Lang::get('language.select_country') ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <label class="col-12 control-label" for="province">{{ __('language.province') }}</label>
                                <div class="col-12">
                                    {!! Form::select('province', [], null, [ 'id' => 'province', 'class' => 'form-control', 'placeholder' => Lang::get('language.select_state') ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12 text-center">
                                <h4 class="mar-btm">{{ __('language.image_city') }}</h4>
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
