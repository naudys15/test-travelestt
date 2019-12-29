@extends('front_office.layouts.users_template')

@section('content')
    <!-- LOGIN FORM -->
    <!--===================================================-->
    <div class="cls-content">
        <div class="cls-content-sm panel">
            <div class="panel-body">
                <div class="mar-ver pad-btm">
                    <h1 class="h3">{{ __('language.new_password') }}</h1>
                </div>
                {!! Form::open([ 'id' => 'form_new_password', 'class' => 'form', 'method' => 'post' ]) !!}
                    @if (isset($data['key']))
                        {!! Form::hidden('key', $data['key']) !!}
                    @endif
                    <div class="form-group">
                        {!! Form::password('password', [ 'id' => 'password', 'class' => 'form-control', 'placeholder' => Lang::get('language.password') ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::password('password_confirmation', [ 'id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => Lang::get('language.confirm_password') ]) !!}
                    </div>
                    @isset($data['error'])
                        <div class="well">
                            <p>{{ $data['error'] }}</p>
                        </div>
                    @endisset
                    <button class="btn btn-mint btn-lg btn-block" type="button">{{ __('language.change_password') }}</button>
                {!! Form::close() !!}
                <div class="pad-all">
                    <a href="{{ url(app()->getLocale().'/'.__('routes.register')) }}" class="mar-lft">{{ __('language.sign_up') }}</a>
                </div>
            </div>
        </div>
    </div>
    <!--===================================================-->
@endsection