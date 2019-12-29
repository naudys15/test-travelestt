@extends('front_office.layouts.template')

@section('content')
    <section id="about_us_hero" style="background-image:url({{ asset('assets/images/home_bg.png') }})" class="bg_img d-flex align-items-center margin_header page_hero">
        <div class="container page_hero_content">
            <div class="row">
                <div class="col-12 text-center text-white">
                    <h2 class="font-weight-bold">{{ __('language.about_us') }}</h2>
            <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <p class="">{{ __('language.about_us_subhead') }}</p>
            </div>
            </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 text-center mb-3">
                    <span><i class="fas fa-plane text-main" style="font-size:3.5rem"></i></span>
                    <h3 class="font-weight-bold text-secondary mt-2 mb-1">+870</h3>
                    <span class="text-secondary">{{ __('language.travels') }}</span>
                </div>
                <div class="col-lg-4 text-center mb-3">
                    <span><i class="fas fa-suitcase text-main" style="font-size:3.5rem"></i></span>
                    <h3 class="font-weight-bold text-secondary mt-2 mb-1">+21.000</h3>
                    <span class="text-secondary">{{ __('language.activities') }}</span>
                </div>
                <div class="col-lg-4 text-center mb-3">
                    <span><i class="fas fa-users text-main" style="font-size:3.5rem"></i></span>
                    <h3 class="font-weight-bold text-secondary mt-2 mb-1">+2.670.000</h3>
                    <span class="text-secondary">{{ __('language.annual_clients') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="font-weight-bold text-dark my-5">{{ __('language.team_that_makes_it_possible') }}</h2>
                </div>
                @for ($i = 0; $i < 12; $i++)
                    <div class="col-lg-3 mb-3">
                        <div class="team_person" style="overflow:hidden">
                        <img src="{{ asset('assets/images/quienes-somos.jpg')}}" alt="">
                                <p class="text-center text-main mb-1">Noelia Carro</p>
                            <p class="text-center text-secondary">Customer Care Representative</p>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
@endsection