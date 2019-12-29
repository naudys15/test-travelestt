@extends('front_office.layouts.template')

@section('content')
    <section id="lugares_cercanos_hero" style="background-image:url({{ asset('assets/images/lugares_cercanos_hero.png') }}?>)" class="bg_img d-flex align-items-center margin_header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center text-white">
                    <h2 class="font-weight-bold">{{ __('language.museums') }}</h2>
                    <p class="lead" id="city"></p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5">
	    <div class="container">
		    <div class="row">
                @include('front_office.layouts.museums.sidebar')
                <div class="col-sm-8 col-md-9 col-lg-10">
                    <div class="row">
                        <div class="col-12 d-flex align-items-center" id="filtro">
                            <span class="font-weight-bold mb-3 mr-3">{{ __('language.order_by') }}</span>
                            <div id="filtro_botones" class="d-flex justify-content-between">
                                <button type="button" id="sort_museums1" class="btn btn-outline-dark rounded-pill text-secondary">{{ __('language.sort_name') }}</button>
                                <button type="button" id="sort_museums2" class="btn btn-outline-dark rounded-pill text-secondary mx-1">{{ __('language.sort_distance') }}</button>
                                <button type="button" id="sort_museums3" class="btn btn-outline-dark rounded-pill text-secondary">{{ __('language.sort_valoration') }}</button>
                            </div>
                        </div>

                    </div>
                    <div id="to_do_content" class="row"></div>
                </div>
		    </div>
        </div>
    </section>
@endsection

@section('script')
@endsection
