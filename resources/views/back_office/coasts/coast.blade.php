@extends('back_office.layouts.template')

@section('content')
	{{ Form::open(array('url' => $data['url'], 'id' => 'form_site', 'class' => 'form', 'method' => $data['method'], 'files' => true )) }}
        <div class="panel-body pad-no">
            <div class="col-xs-12 pad-no">
                <div id="mapid" class="front_search"></div>
                    <div id="search">
                        <input type="text" name="addr" value="" id="addr" size="10" />
                        <span>
                            <button type="button" onclick="addrSearch()">{{ __('language.search') }}</button>
                            <button type="button" onclick="clearSearch()">{{ __('language.clear') }}</button>
                        </span>
                    <div id="results"></div>
                </div>
            </div>
        </div>
        <div class="row" id="setLanguage" onclick="setLanguage()">
            <!-- informacion -->
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group">
                        <!-- galeria de imagenes-->
                        <div class="col-xs-12" id="eventImage">
                            <div class="panel">
                                <div class="panel-body pad-no">
                                    <h4 class="mar-btm">{{ __('language.image_gallery') }}</h4>
                                    <!--Dropzonejs-->
                                    <!--===================================================-->
                                    <div id="dropzoneFiles" action="#" class="">
                                    <div class="dz-default dz-message">
                                        <div class="dz-icon">
                                        <i class="demo-pli-upload-to-cloud icon-5x"></i>
                                        </div>
                                        <div>
                                        <span class="dz-text">{{ __('language.drop_files_to_upload') }}</span>
                                        <p class="text-sm text-muted">{{ __('language.or_click_to_pick_manually') }}</p>
                                        </div>
                                    </div>
                                    </div>
                                    <!--===================================================-->
                                    <!-- End Dropzonejs -->
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12" id="eventDescription">
                        </div>
                    </div>

                    <div class="col-12">
                        <h4 class="mar-btm">{{ __('language.coast') }}</h4>
                    </div>

                    <!--Url para ver en front-->
                    <div class="form-group">
                        <div class="">
                            <label class="col-12 control-label" for="url">URL</label>
                            <a id="url" href="#" target="_blank">URL</a>
                        </div>
                    </div>

                    <!--Slug-->
                    <div class="form-group">
                        <div class="">
                            <label class="col-12 control-label" for="slug">Slug</label>
                            <div class="col-12">
                                {!! Form::text('slug', null, [ 'id' => 'slug', 'class' => 'form-control', 'placeholder' => 'Slug' ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="tab-base">
                        <!--Nav Tabs-->
                        <ul class="nav nav-tabs">
                            <li id="pill_spanish" class="active">
                                <a data-toggle="tab" href="#spanish" aria-expanded="false">{{ __('language.spanish') }}</a>
                            </li>
                            <li id="pill_english" class="">
                                <a data-toggle="tab" href="#english" aria-expanded="false">{{ __('language.english') }}</a>
                            </li>
                            <li id="pill_french" class="">
                                <a data-toggle="tab" href="#french" aria-expanded="false">{{ __('language.french') }}</a>
                            </li>
                        </ul>

                        <!--Tabs Content-->
                        <div class="tab-content">
                            <div id="spanish" class="tab-pane fade">
                                <!--Text Input-->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="name">{{ __('language.name') }}</label>
                                        <div class="col-12">
                                            {!! Form::text('name_spanish', null, [ 'id' => 'name_spanish', 'class' => 'form-control', 'placeholder' => Lang::get('language.name') ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="short_information">{{ __('language.short_description') }}</label>
                                        <div class="col-12">
                                            {!! Form::textarea('short_information_spanish', null, [ 'id' => 'short_information_spanish', 'class' => 'form-control site_information', 'placeholder' => Lang::get('language.short_description'), 'rows' => '3' ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="long_information">{{ __('language.long_description') }}</label>
                                        <div class="col-12">
                                            {!! Form::textarea('long_information_spanish', null, [ 'id' => 'long_information_spanish', 'class' => 'form-control site_information', 'placeholder' => Lang::get('language.long_description'), 'rows' => '3' ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="address">{{ __('language.address') }}</label>
                                        <div class="col-12">
                                            {!! Form::textarea('address_spanish', null, [ 'id' => 'address_spanish', 'class' => 'form-control address_spanish', 'placeholder' => Lang::get('language.address'), 'rows' => '3' ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!--Text Input-->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="meta_title">{{ __('language.metatitle') }}</label>
                                        <div class="col-12">
                                            {!! Form::text('meta_title_spanish', null, [ 'id' => 'meta_title_spanish', 'class' => 'form-control', 'placeholder' => Lang::get('language.metatitle') ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!--Text Input-->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="meta_description">{{ __('language.metadescription') }}</label>
                                        <div class="col-12">
                                            {!! Form::text('meta_description_spanish', null, [ 'id' => 'meta_description_spanish', 'class' => 'form-control', 'placeholder' => Lang::get('language.metadescription') ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="english" class="tab-pane fade">
                                <!--Text Input-->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="name">{{ __('language.name') }}</label>
                                        <div class="col-12">
                                            {!! Form::text('name_english', null, [ 'id' => 'name_english', 'class' => 'form-control', 'placeholder' => Lang::get('language.name') ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="short_information">{{ __('language.short_description') }}</label>
                                        <div class="col-12">
                                            {!! Form::textarea('short_information_english', null, [ 'id' => 'short_information_english', 'class' => 'form-control site_information', 'placeholder' => Lang::get('language.short_description'), 'rows' => '3' ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="long_information">{{ __('language.long_description') }}</label>
                                        <div class="col-12">
                                            {!! Form::textarea('long_information_english', null, [ 'id' => 'long_information_english', 'class' => 'form-control site_information', 'placeholder' => Lang::get('language.long_description'), 'rows' => '3' ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="address">{{ __('language.address') }}</label>
                                        <div class="col-12">
                                            {!! Form::textarea('address_english', null, [ 'id' => 'address_english', 'class' => 'form-control address_english', 'placeholder' => Lang::get('language.address'), 'rows' => '3' ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!--Text Input-->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="meta_title">{{ __('language.metatitle') }}</label>
                                        <div class="col-12">
                                            {!! Form::text('meta_title_english', null, [ 'id' => 'meta_title_english', 'class' => 'form-control', 'placeholder' => Lang::get('language.metatitle') ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!--Text Input-->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="meta_description">{{ __('language.metadescription') }}</label>
                                        <div class="col-12">
                                            {!! Form::text('meta_description_english', null, [ 'id' => 'meta_description_english', 'class' => 'form-control', 'placeholder' => Lang::get('language.metadescription') ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="french" class="tab-pane fade">
                                <!--Text Input-->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="name">{{ __('language.name') }}</label>
                                        <div class="col-12">
                                            {!! Form::text('name_french', null, [ 'id' => 'name_french', 'class' => 'form-control', 'placeholder' => Lang::get('language.name') ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="short_information">{{ __('language.short_description') }}</label>
                                        <div class="col-12">
                                            {!! Form::textarea('short_information_french', null, [ 'id' => 'short_information_french', 'class' => 'form-control site_information', 'placeholder' => Lang::get('language.short_description'), 'rows' => '3' ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="long_information">{{ __('language.long_description') }}</label>
                                        <div class="col-12">
                                            {!! Form::textarea('long_information_french', null, [ 'id' => 'long_information_french', 'class' => 'form-control site_information', 'placeholder' => Lang::get('language.long_description'), 'rows' => '3' ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="address">{{ __('language.address') }}</label>
                                        <div class="col-12">
                                            {!! Form::textarea('address_french', null, [ 'id' => 'address_french', 'class' => 'form-control address_french', 'placeholder' => Lang::get('language.address'), 'rows' => '3' ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!--Text Input-->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="meta_title">{{ __('language.metatitle') }}</label>
                                        <div class="col-12">
                                            {!! Form::text('meta_title_french', null, [ 'id' => 'meta_title_french', 'class' => 'form-control', 'placeholder' => Lang::get('language.metatitle') ]) !!}
                                        </div>
                                    </div>
                                </div>
                                <!--Text Input-->
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-12 control-label" for="meta_description">{{ __('language.metadescription') }}</label>
                                        <div class="col-12">
                                            {!! Form::text('meta_description_french', null, [ 'id' => 'meta_description_french', 'class' => 'form-control', 'placeholder' => Lang::get('language.metadescription') ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-4 p-2">
                            <div class="col-xs-12 text-center">
                                <label class="control-label" for="type_sand">{{ __('language.type_sands') }}</label><br>
                            </div>
                            <div class="col-md-6">
                                <div class="radio">
                                    <input class="magic-radio" type="radio" id="type_sand_1" placeholder="Arena blanca" name="type_sand" value="1"><label for="type_sand_1">{{__('language.white_sand') }}</label>
                                </div>
                                <div class="radio">
                                    <input class="magic-radio" type="radio" id="type_sand_2" placeholder="Arena amarilla" name="type_sand" value="2"><label for="type_sand_2">{{__('language.yellow_sand') }}</label>
                                </div>
                                <div class="radio">
                                    <input class="magic-radio" type="radio" id="type_sand_3" placeholder="Arena verde" name="type_sand" value="3"><label for="type_sand_3">{{__('language.green_sand') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="radio">
                                    <input class="magic-radio" type="radio" id="type_sand_4" placeholder="Arena negra" name="type_sand" value="4"><label for="type_sand_4">{{__('language.black_sand') }}</label>
                                </div>
                                <div class="radio">
                                    <input class="magic-radio" type="radio" id="type_sand_5" placeholder="Piedra fina" name="type_sand" value="5"><label for="type_sand_5">{{__('language.fine_stone') }}</label>
                                </div>
                                <div class="radio">
                                    <input class="magic-radio" type="radio" id="type_sand_6" placeholder="Piedra gruesa" name="type_sand" value="6"><label for="type_sand_6">{{__('language.thick_stone') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 p-2">
                            <div class="col-xs-12 text-center">
                                <label class="control-label" for="extras">{{ __('language.extras') }}</label>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <input class="magic-checkbox extras" type="checkbox" id="extras_1" placeholder="{{ __('language.feet_lavatory') }}" name="extras_feet_lavatory" value="feet_lavatory"><label for="extras_1">{{__('language.feet_lavatory') }}</label>
                                </div>
                                <div class="checkbox">
                                    <input class="magic-checkbox extras" type="checkbox" id="extras_2" placeholder="{{ __('language.showers') }}" name="extras_showers" value="showers"><label for="extras_2">{{__('language.showers') }}</label>
                                </div>
                                <div class="checkbox">
                                    <input class="magic-checkbox extras" type="checkbox" id="extras_3" placeholder="{{ __('language.lifeguards') }}" name="extras_lifeguards" value="lifeguards"><label for="extras_3">{{__('language.lifeguards') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <input class="magic-checkbox extras" type="checkbox" id="extras_4" placeholder="{{ __('language.handicapped_access') }}" name="extras_handicapped_access" value="handicapped_access"><label for="extras_4">{{__('language.handicapped_access') }}</label>
                                </div>
                                <div class="checkbox">
                                    <input class="magic-checkbox extras" type="checkbox" id="extras_5" placeholder="{{ __('language.watersports') }}" name="extras_watersports" value="watersports"><label for="extras_5">{{__('language.watersports') }}</label>
                                </div>
                                <div class="checkbox">
                                    <input class="magic-checkbox extras" type="checkbox" id="extras_6" placeholder="{{ __('language.chiringuito') }}" name="extras_chiringuito" value="chiringuito"><label for="extras_6">{{__('language.chiringuito') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 p-2">
                            <div class="col-xs-12 text-center">
                                <label class="control-label" for="stamps">{{ __('language.stamps') }}</label><br>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <input class="magic-checkbox stamps" type="checkbox" id="stamps_1" placeholder="{{ __('language.iso_14001') }}" name="stamps_iso_14001" value="iso_14001"><label for="stamps_1">{{__('language.iso_14001') }}</label>
                                </div>
                                <div class="checkbox">
                                    <input class="magic-checkbox stamps" type="checkbox" id="stamps_2" placeholder="{{ __('language.iso_9001') }}" name="stamps_iso_9001" value="iso_9001"><label for="stamps_2">{{__('language.iso_9001') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <input class="magic-checkbox stamps" type="checkbox" id="stamps_3" placeholder="{{ __('language.iso_17001') }}" name="stamps_iso_17001" value="iso_17001"><label for="stamps_3">{{__('language.iso_17001') }}</label>
                                </div>
                                <div class="checkbox">
                                    <input class="magic-checkbox stamps" type="checkbox" id="stamps_4" placeholder="{{ __('language.blue_flag') }}" name="stamps_blue_flag" value="blue_flag"><label for="stamps_4">{{__('language.blue_flag') }}</label>
                                </div>
                                <div class="checkbox">
                                    <input class="magic-checkbox stamps" type="checkbox" id="stamps_5" placeholder="{{ __('language.qualitur_flag') }}" name="stamps_qualitur_flag" value="qualitur_flag"><label for="stamps_5">{{__('language.qualitur_flag') }}</label>
                            </div>
                        </div>
                    </div> --}}

                    <!-- <div class="form-group">
                        <div class="row">
                            <label class="col-12 control-label" for="duration_value"><?php //_l('visiting_time'); ?><br><small><?php //echo _l('(approximate)'); ?></small></label>
                            <div class="col-12">
                            <div class="row">
                                <div class="col-sm-4">
                                <input type="number" id="duration_value" class="form-control" placeholder="<?php //echo _l('time'); ?>" name="duration_value" value="<?php if (isset($coast)) {  if (is_array($coast)) { echo $coast['duration']['value']; }} ?>">
                                </div>
                                <div class="col-sm-8">
                                <?php
                                    /*if (isset($coast)) {
                                    if (is_array($coast)) {
                                        echo form_dropdown('duration_unit', ['minutes' => _l('minutes'), 'hours' => _l('hours')], $coast['duration']['unit'], ['id'=>'duration_unit', 'class'=>'form-control']);
                                    } else {
                                        echo form_dropdown('duration_unit', ['minutes' => _l('minutes'), 'hours' => _l('hours')], '', ['id'=>'duration_unit', 'class'=>'form-control']);
                                    }
                                    } else {
                                    echo form_dropdown('duration_unit', ['minutes' => _l('minutes'), 'hours' => _l('hours')], '', ['id'=>'duration_unit', 'class'=>'form-control']);
                                    }*/
                                ?>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <div class="">
                            <label class="col-12 control-label" for="state">{{ __('language.province') }}</label>
                            <div class="col-12">
                                {!! Form::select('state', [], null, [ 'id' => 'state', 'class' => 'form-control', 'placeholder' => Lang::get('language.select_state') ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="">
                            <label class="col-12 control-label" for="city">{{ __('language.city') }}</label>
                            <div class="col-12">
                                {!! Form::select('city', [], null, [ 'id' => 'city', 'class' => 'form-control', 'placeholder' => Lang::get('language.select_city') ]) !!}
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
