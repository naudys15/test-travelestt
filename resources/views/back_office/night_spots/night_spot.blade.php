@extends('back_office.layouts.template')

@section('content')
	{{ Form::open(array('url' => $data['url'], 'id' => 'form_site', 'class' => 'form', 'method' => $data['method'], 'files' => true )) }}
        <div class="panel"  id="eventMarker">
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
        </div>
        <div class="row" id="setLanguage" onclick="setLanguage('spanish')">
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

                    <h4 class="mar-btm">{{ __('language.night_spot') }}</h4>

                    <!--Url para ver en front-->
                    <div class="form-group">
                        <div class="">
                            <label class="col-12 control-label" for="url">URL</label>
                            <a id="url" href="#" target="_blank">URL</a>
                        </div>
                    </div>

                    <!--Slug-->
                    <div class="form-group">
                        <div class="row">
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

                    <!-- <div class="form-group">
                        <div class="row">
                            <label class="col-12 control-label" for="duration_value"><?php //_l('visiting_time'); ?><br><small><?php //echo _l('(approximate)'); ?></small></label>
                            <div class="col-12">
                            <div class="row">
                                <div class="col-sm-4">
                                <input type="number" id="duration_value" class="form-control" placeholder="<?php //echo _l('time'); ?>" name="duration_value" value="<?php if (isset($night_spot)) {  if (is_array($night_spot)) { echo $night_spot['duration']['value']; }} ?>">
                                </div>
                                <div class="col-sm-8">
                                <?php
                                    /*if (isset($night_spot)) {
                                    if (is_array($night_spot)) {
                                        echo form_dropdown('duration_unit', ['minutes' => _l('minutes'), 'hours' => _l('hours')], $night_spot['duration']['unit'], ['id'=>'duration_unit', 'class'=>'form-control']);
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
                        <div class="row">
                            <label class="col-12 control-label">{{ __('language.phonenumber') }}</label>
                            <div class="col-12">
                                    {!! Form::text('phonenumber', null, [ 'id' => 'phonenumber', 'class' => 'form-control', 'placeholder' => Lang::get('language.phonenumber') ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-12 control-label">{{ __('language.province') }}</label>
                            <div class="col-12">
                                {!! Form::select('state', [], null, [ 'id' => 'state', 'class' => 'form-control', 'placeholder' => Lang::get('language.select_state') ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
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
	