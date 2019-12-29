@extends('front_office.layouts.users_template')

@section('content')
    <!-- RECOVERY FORM -->
	<!--===================================================-->
	<div class="cls-content">
        <div class="cls-content-sm panel">
            <div class="panel-body">
                <div class="mar-ver pad-btm">
                    <h1 class="h3">{{ __('language.recovery_password') }}</h1>
                </div>
                {!! Form::open([ 'id' => 'form_recovery_password', 'class' => 'form', 'method' => 'post' ]) !!}
                    <div class="form-group">
                        {!! Form::email('email', null, [ 'id' => 'email', 'class' => 'form-control', 'placeholder' => Lang::get('language.email') ]) !!}
                    </div>
                    <button class="btn btn-mint btn-lg btn-block" type="button">{{ __('language.recovery') }}</button>
                {!! Form::close() !!}
            </div>
            <div class="pad-all">
                <a href="{{ url(app()->getLocale().'/') }}" class="mar-rgt text-bold">{{ __('language.home') }}</a>
                {{ __('language.have_an_account') }} <a href="{{ url(app()->getLocale().'/'.__('routes.register')) }}" class="mar-rgt text-bold">{{ __('language.sign_up') }}</a>
            </div>
        </div>
    </div>
    <!--===================================================-->
@endsection