@extends('front_office.layouts.users_template')

@section('content')
    <!-- REGISTRATION FORM -->
    <!--===================================================-->
    <div class="cls-content">
        <div class="cls-content-lg panel">
            <div class="panel-body">
                <div class="mar-ver pad-btm">
                    <h1 class="h3">{{ __('language.sign_up_agency') }}</h1>
                    <p>{{ __('language.agency_data') }}</p>
                </div>
                {!! Form::open([ 'id' => 'form_register_agency', 'class' => 'form']) !!}
                    @csrf
                    <!-- nombre y apellido -->
                    <div class="row">
                        <div class="col-sm-6 form-group my-2">
                            {!! Form::text('commercial_name', null, [ 'id' => 'commercial_name', 'class' => 'form-control', 'placeholder' => Lang::get('language.commercial_name') ]) !!}
                        </div>
                        <div class="col-sm-6 form-group my-2">
                            {!! Form::select('licence', [ 'iata '=> 'IATA' ], null, [ 'id' => 'licence', 'class' => 'form-control', 'placeholder' => Lang::get('language.licence') ]) !!}
                        </div>
                    </div>
                    <!-- identificador de agencia y pagina web -->
                    <div class="row">
                        <div class="col-sm-6 form-group my-2">
                            {!! Form::text('agency_identifier', null, [ 'id' => 'agency_identifier', 'class' => 'form-control', 'placeholder' => Lang::get('language.agency_identifier') ]) !!}
                        </div>
                        <div class="col-sm-6 form-group my-2">
                            {!! Form::text('web_page', null, [ 'id' => 'web_page', 'class' => 'form-control', 'placeholder' => Lang::get('language.web_page') ]) !!}
                        </div>
                    </div>
                    <!-- NIF y razon social -->
                    <div class="row">
                        <div class="col-sm-6 form-group my-2">
                            {!! Form::select('business_name', [], null, [ 'id' => 'business_name', 'class' => 'form-control', 'placeholder' => Lang::get('language.business_name') ]) !!}
                        </div>
                        <div class="col-sm-6 form-group my-2">
                            {!! Form::select('nif', [], null, [ 'id' => 'nif', 'class' => 'form-control', 'placeholder' => Lang::get('language.nif') ]) !!}
                        </div>
                    </div>
                    <!-- identificador fiscal y direccion fiscal -->
                    <div class="row">
                        <div class="col-sm-6 form-group my-2">
                            {!! Form::text('tax_identifier', null, [ 'id' => 'tax_identifier', 'class' => 'form-control', 'placeholder' => Lang::get('language.tax_identifier') ]) !!}
                        </div>
                        <div class="col-sm-6 form-group my-2">
                            {!! Form::text('fiscal_address', null, [ 'id' => 'fiscal_address', 'class' => 'form-control', 'placeholder' => Lang::get('language.fiscal_address') ]) !!}
                        </div>
                    </div>
                    

                    <!-- pais y ciudad -->
                    <div class="row">
                        <div class="col-sm-6 form-group my-2">
                            {!! Form::select('country', [], null, [ 'id' => 'country', 'class' => 'form-control', 'placeholder' => Lang::get('language.select_country') ]) !!}
                        </div>
                        <div class="col-sm-6 form-group my-2">
                            {!! Form::select('city', [], null, [ 'id' => 'city', 'class' => 'form-control', 'placeholder' => Lang::get('language.select_city') ]) !!}
                        </div>
                    </div>
                    <h4>{{Lang::get('language.access_data')}}</h4>
                    <hr>
                    <!-- nombre y apellido -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 form-group my-2">
                            {!! Form::text('firstname', null, [ 'id' => 'firstname', 'class' => 'form-control', 'placeholder' => Lang::get('language.firstname') ]) !!}
                        </div>
                        <div class="col-xs-12 col-sm-6 form-group my-2">
                            {!! Form::text('lastname', null, [ 'id' => 'lastname', 'class' => 'form-control', 'placeholder' => Lang::get('language.lastname') ]) !!}
                        </div>
                    </div>
                    <!-- email y telefono -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 form-group my-2">
                            {!! Form::email('email', null, [ 'id' => 'email', 'class' => 'form-control', 'placeholder' => Lang::get('language.email') ]) !!}
                        </div>
                        <div class="col-xs-12 col-sm-6 form-group my-2">
                            {!! Form::text('phonenumber', null, [ 'id' => 'phonenumber', 'class' => 'form-control', 'placeholder' => Lang::get('language.phonenumber') ]) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 form-group my-2">
                            {!! Form::password('password', [ 'id' => 'password', 'class' => 'form-control', 'placeholder' => Lang::get('language.password') ]) !!}
                        </div>
                        <div class="col-xs-12 col-sm-6 form-group my-2">
                            {!! Form::password('password_confirmation', [ 'id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => Lang::get('language.confirm_password') ]) !!}
                        </div>
                    </div>
                    <div class="col-12 form-group">
                        <input id="terms_and_conditions" class="magic-checkbox" type="checkbox" name="terms_and_conditions">
                        <label for="terms_and_conditions">{{ __('language.i_agree') }} <a href="#" class="text-bold">{{ __('language.terms_and_conditions') }}</a></label>
                    </div>
                    <button class="btn btn-mint btn-lg btn-block" type="submit">{{ __('language.sign_up') }}</button>
                {!! Form::close() !!}
            </div>
            <div class="pad-all">
                <a href="{{ url(app()->getLocale().'/') }}" class="mar-rgt text-bold">{{ __('language.home') }}</a>
                <a href="{{ url(app()->getLocale().'/'.__('routes.recovery_password')) }}" class="mar-rgt text-bold">{{ __('language.forgot_your_password') }} </a>
                <div class="media pad-top bord-top">
                    <div class="pull-right">
                        <a href="#" class="pad-rgt"><i class="fab fa-facebook-f icon-lg text-primary"></i></a>
                        <a href="#" class="pad-rgt"><i class="fab fa-google icon-lg text-danger"></i></a>
                    </div>
                    <div class="mephpdia-body text-left text-main">
                        {{ __('language.sign_up_with') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===================================================-->
@endsection