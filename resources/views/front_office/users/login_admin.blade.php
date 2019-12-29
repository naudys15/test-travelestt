@extends('front_office.layouts.users_template')

@section('content')
    <<!-- LOGIN FORM -->
	<!--===================================================-->
	<div class="cls-content">
        <div class="cls-content-sm panel">
            <div class="panel-body">
                <div class="mar-ver pad-btm">
                <h1 class="h3">{{ __('language.access_to_panel') }}</h1>
                    <p>{{ __('language.access_for_administrators_only') }}</p>
                </div>
                {!! Form::open([ 'id' => 'form_login', 'class' => 'form']) !!}
                    @csrf
                    <div class="form-group">
                        {!! Form::email('email', null, [ 'id' => 'email', 'class' => 'form-control', 'placeholder' => Lang::get('language.email') ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::password('password', [ 'id' => 'password', 'class' => 'form-control', 'placeholder' => Lang::get('language.password') ]) !!}
                    </div>
                    <button class="btn btn-mint btn-lg btn-block" type="submit">{{ __('language.log_in') }}</button>
                </form>
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