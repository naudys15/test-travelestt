@extends('front_office.layouts.template')

@section('content')
    <div id="hero_home" class="d-flex jumbotron jumbotron-fluid bg_img h-50 mb-0 margin_header">
        <div id="hero_container" class="container text-white align-self-center">
            <div class="hero_content text-center">
            <h1 class="display-4 font-weight-bold"></h1>
            <span class="badge badge-pill badge-warning px-3"></span>
            <p class="mt-3">{{ __('language.discover_amazing_places') }}<br>{{ __('language.near_you') }}</p>
            </div>
        </div>
    </div>

    <div id="lugares_naturales_cerca" class="mb-4">
        <div class="container">
            <div class="row">
            <div class="col-12 lead text-light mb-2">Lugares naturales cerca</div>
            @for($i = 1; $i <= 4; $i++)
            <div class="col-sm-6 col-md-3 lugar_cerca mb-3">
                <a href="#" class="lugar_cerca_content d-flex flex-column justify-content-center text-center bg_img position-relative" style="background-image:url({{ asset('assets/images/senderos/sendero.jpg')}})">
                <div class="dark_item position-absolute w-100 h-100"></div>
                <div class="lugar_cerca_title">Font Roja</div>
                <small class="lugar_cerca_distance">(a 11,3 km)</small>
                </a>
            </div>
            @endfor
            </div>
        </div>
    </div>

    <section id="cerca_de_mi" class="">
        <div class="container">
            <div class="row">
                <div class="col-12 position-relative">
                    <div id="mapid" class=""></div>
                </div>
            </div>
        </div>
    </section>

    <section id="lugares_ciudad" class="my-4">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-4">
                    <div id="content_form" class="mb-3">
                        <form action="" class="">
                            <div class="input-group bg-white py-1 rounded pr-1 border">
                                <input id="search_hero_input" type="text" placeholder="{{ __('language.what_do_you_want_to_do_in') }}" class="form-control">
                                <span class="input-group-btn">
                                    <button id="search_hero_button" class="btn btn-main px-4" type="button"><i class="fa fa-search"></i> {{ __('language.search') }}</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex mb-3">
                    <h2 class="font-weight-bold mb-0 text-dark">{{ __('language.what_to_do_in') }}</h2>
                </div>
                <div class="col-12 mb-4" id="guided_visits">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-road mr-3 text-aqua"></i>{{ __('language.guided_visits') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-guided_visits"></small>
                    </div>
                    <div class="col-12 carrousel-guided_visits">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
                <div class="col-12 mb-4" id="organized_trips">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-road mr-3 text-aqua"></i>{{ __('language.organized_trips') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-organized_trips"></small>
                    </div>
                    <div class="col-12 carrousel-organized_trips">
                        <div class="destinos_container owl-carousel owl-theme">

                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
                <div class="col-12 mb-4" id="experiences">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-road mr-3 text-aqua"></i>{{ __('language.multi_adventure_experiences') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-experiences"></small>
                    </div>
                    <div class="col-12 carrousel-experiences">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
                <div class="col-12 mb-4" id="festivals">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-drum mr-3 text-aqua"></i>{{ __('language.festivals_and_shows') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-festivals"></small>
                    </div>
                    <div class="col-12 carrousel-festivals">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
                <div class="col-12 mb-4" id="routes_btt">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-route mr-3 text-aqua"></i>{{ __('language.routes_btt') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-routes_btt"></small>
                    </div>
                    <div class="col-12 carrousel-routes_btt">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
                <div class="col-12 mb-4" id="routes_highway">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-route mr-3 text-aqua"></i>{{ __('language.routes_highway') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-routes_highway"></small>
                    </div>
                    <div class="col-12 carrousel-routes_highway">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
                <div class="col-12 mb-4" id="routes_hiker">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-route mr-3 text-aqua"></i>{{ __('language.routes_hiker') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-routes_hiker"></small>
                    </div>
                    <div class="col-12 carrousel-routes_hiker">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex mb-3">
                    <h2 class="font-weight-bold mb-0 text-dark">{{ __('language.what_to_see_in') }}</h2>
                </div>
                <div class="col-12 mb-4" id="points_of_interest">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-archway mr-3 text-aqua"></i>{{ __('language.points_of_interest') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-points_of_interest"></small>
                    </div>
                    <div class="col-12 carrousel-points_of_interest">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
                <div class="col-12 mb-4" id="night_spots">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-glass-martini-alt mr-3 text-aqua"></i>{{ __('language.night_spots') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-night_spots"></small>
                    </div>
                    <div class="col-12 carrousel-night_spots">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
                <div class="col-12 mb-4" id="coasts">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-umbrella-beach mr-3 text-aqua"></i>{{ __('language.coasts') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-coasts"></small>
                    </div>
                    <div class="col-12 carrousel-coasts">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
                <div class="col-12 mb-4" id="museums">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-landmark mr-3 text-aqua"></i>{{ __('language.museums') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-museums"></small>
                    </div>
                    <div class="col-12 carrousel-museums">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
                <div class="col-12 mb-4" id="street_markets">
                    <div class="col-12 d-flex mb-3">
                        <h3 class="font-weight-bold mb-0 text-dark"><i class="fas fa-store-alt mr-3 text-aqua"></i>{{ __('language.street_markets') }}</h3>
                        <small class="align-self-center text-secondary ml-2 count-street_markets"></small>
                    </div>
                    <div class="col-12 carrousel-street_markets">
                        <div class="destinos_container owl-carousel owl-theme">
                        </div>
                    </div>
                    <div class="col-12 my-4 text-center">
                        <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex mb-3">
                    <h2 class="font-weight-bold mb-0 text-dark">{{  __('language.where_to_eat_in') }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex mb-3">
                    <h2 class="font-weight-bold mb-0 text-dark">{{  __('language.where_to_sleep_in') }}</h2>
                </div>
            </div>
        </div>
    </section>
@endsection
