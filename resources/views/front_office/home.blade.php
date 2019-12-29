@extends('front_office.layouts.template')

@section('content')
    <div id="hero_home" class="d-flex jumbotron jumbotron-fluid bg_img h-60 mb-0 margin_header">
        <div id="hero_container" class="container text-white align-self-center">
        <div class="hero_content text-center">
            <h1 class="display-4 font-weight-bold">{{ __('language.main_title') }}</h1>
            <p class="lead">{{ __('language.main_subtitle') }}</p>
            <div id="hero_content_form" class="mt-4">
            <form action="" class="">
                <div class="input-group mar-btm col-lg-6 offset-lg-3 bg-white py-1 rounded pr-1">
                    <input id="search_hero_input" type="text" placeholder="{{ __('language.search_destination') }}" class="form-control">
                    <span class="input-group-btn">
                        <button id="search_hero_button" class="btn btn-main px-4" type="button">{{ __('language.search') }}</button>
                    </span>
                    <div id="top_destinos" class="bg-white position-absolute py-3 text-dark">
                        <div id="top_destinos_content" data-scrollbar>
                            <div class="row" >
                                <div class="col-12">
                                    <h5 class="font-weight-bold mb-4">{{ __('language.top_destinations') }}</h5>
                                </div>
                                {{-- <div class="col-md-4 text-left">
                                    <ul class="">
                                    <li>
                                        <a href=""><strong>Barcelona</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    <li>
                                        <a href=""><strong>Madrid</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    <li>
                                        <a href=""><strong>Alcoy</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    <li>
                                        <a href=""><strong>Zaragoza</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-md-4 text-left">
                                    <ul class="">
                                    <li>
                                        <a href=""><strong>Barcelona</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    <li>
                                        <a href=""><strong>Madrid</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    <li>
                                        <a href=""><strong>Alcoy</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    <li>
                                        <a href=""><strong>Zaragoza</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-md-4 text-left">
                                    <ul class="">
                                    <li>
                                        <a href=""><strong>Barcelona</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    <li>
                                        <a href=""><strong>Madrid</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    <li>
                                        <a href=""><strong>Alcoy</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    <li>
                                        <a href=""><strong>Zaragoza</strong><br><span class="text-secondary">España</span></a>
                                    </li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>

    <!-- principales destinos -->
    <section id="principales_destinos" class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- titulo con link ver mas -->
                    <div class="row">
                    <div class="col-12 d-flex">
                        <h3 class="font-weight-bold mb-4 text-dark"><i class="fas fa-globe-americas mr-3 text-aqua"></i>{{ __('language.main_destinations') }}</h3>
                    </div>
                    </div>
                    <!-- destinos -->
                    <div class="destinos">
                        <div class="row">
                            @for ($i = 0; $i < 6; $i++)
                                {{-- <div class="col-sm-6 col-md-4 destino mb-2">
                                    <div class="destino_img position-relative">
                                        <a href="#">
                                            <div class="bg_img" style="height:225px; background-image: url('{{ asset('assets/images/madrid.jpg') }}')"></div>
                                        </a>
                                        <a class="destino_info position-absolute p-3 w-100" href="#">
                                            <h4 class="ciudad text-white font-weight-bold mb-0">Barcelona</h4>
                                            <div class="text-white"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>56 {{ __('language.opinions') }}</small></div>
                                        </a>
                                    </div>
                                </div> --}}
                            @endfor        
                            {{-- <div class="col-12 mt-4 text-center">
                                <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                            </div> --}}
                        </div>
                    </div>
                    <!-- destinos -->
                </div>
            </div>
        </div>
    </section>

    <!-- viajes organizados -->
    <section id="viajes_organizados" class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- titulo con link ver mas -->
                    <div class="row">
                    <div class="col-12 d-flex">
                        <h3 class="font-weight-bold mb-4 text-dark"><i class="fas fa-map-marked-alt mr-3 text-aqua"></i>{{ __('language.organized_trips') }}</h3>
                    </div>
                    </div>
                    <!-- destinos -->
                    <div class="destinos">
                        <div class="row">
                            @for ($i = 0; $i < 6; $i++)
                                <!-- ruta -->
                                <div class="col-sm-6 col-md-4 destino mb-3">
                                    <div class="destino_img position-relative">
                                        <a href="#">
                                            <div class="experience_items position-absolute w-100 d-flex align-items-center">
                                            <span class="bg-main text-white font-weight-bold py-2 px-3">14% Descuento</span>
                                            <!-- <i class="far fa-heart text-white ml-auto like"></i> -->
                                            </div>
        
                                            <div class="bg_img" style="height:200px; background-image: url('{{ asset('assets/images/fondo.jpg') }}')"></div>
        
                                            <div class="destino_info mt-1 d-flex p-3">
                                            <div>
                                                <p class="font-weight-bold mb-0">3 días en Madrid</p>
                                                <div class="text-secondary"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>56 {{ __('language.opinions') }}</small></div>
                                            </div>
                                            <div class="precio ml-auto align-self-center mr-2 text-secondary"><strong>120$</strong></div>
                                            </div>
                                            <div class="destino_resume position-absolute text-white p-3 d-flex flex-column justify-content-end">
                                            <p class="font-weight-bold mb-1">3 días en Madrid</p>
                                            <div class="text-secondary"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>56 {{ __('language.opinions') }}</small></div>
                                            <p class="destino_descripcion mt-1">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta aut modi, veniam nobis, cum officiis iste. Voluptas animi quam, error aliquam distinctio nulla. Quaerat hic enim, eaque doloribus. Facilis, voluptatum!</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- end ruta -->
                            @endfor        
                            <div class="col-12 mt-4 text-center">
                                <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- experiencias multiaventuras -->
    <section id="experiencias_multiaventuras" class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- titulo con link ver mas -->
                    <div class="row">
                    <div class="col-12 d-flex">
                        <h3 class="font-weight-bold mb-4 text-dark"><i class="fas fa-suitcase mr-3 text-aqua"></i>{{ __('language.multi_adventure_experiences') }}</h3>
                    </div>
                    </div>
                    <!-- destinos -->
                    <div class="destinos">
                        <div class="row">
                            @for ($i = 0; $i < 6; $i++)
                                    <!-- ruta -->
                                <div class="col-sm-6 col-md-4 destino mb-3">
                                    <div class="destino_img position-relative">
                                        <a href="#">
                                            <div class="experience_items position-absolute w-100 d-flex align-items-center">
                                                <span class="bg-main text-white font-weight-bold py-2 px-3">14% Descuento</span>
                                            </div>
        
                                            <div class="bg_img" style="height:200px; background-image: url('{{ asset('assets/images/fondo.jpg') }}')"></div>
        
                                            <div class="destino_info mt-1 d-flex p-3">
                                            <div>
                                                <p class="font-weight-bold mb-0">3 días en Madrid</p>
                                                <div class="text-secondary"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>56 {{ __('language.opinions') }}</small></div>
                                            </div>
                                            <div class="precio ml-auto align-self-center mr-2 text-secondary"><strong>120$</strong></div>
                                            </div>
                                            <div class="destino_resume position-absolute text-white p-3 d-flex flex-column justify-content-end">
                                            <p class="font-weight-bold mb-1">3 días en Madrid</p>
                                            <div class="text-secondary"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>56 {{ __('language.opinions') }}</small></div>
                                            <p class="destino_descripcion mt-1">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta aut modi, veniam nobis, cum officiis iste. Voluptas animi quam, error aliquam distinctio nulla. Quaerat hic enim, eaque doloribus. Facilis, voluptatum!</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- end ruta -->
                            @endfor
                            <div class="col-12 mt-4 text-center">
                                <a href="" class="btn btn-outline-main px-4 py-2">{{ __('language.see_all') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection