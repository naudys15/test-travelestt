    @extends('front_office.layouts.template')

    @section('content')
        <section id="detalle_viaje" class="margin_header py-5">
        {{-- <input id="place" type="hidden" name="place" value="{{$data['city']}}"> --}}
            {{-- <input id="slug_place" type="hidden" name="slug_place" value="{{$data['slug']}}"> --}}
            <div class="container">
                <div class="row">
                    <!-- TITULO -->
                    <div class="col-12">
                        <div class="text-secondary"><small id="city"></small></div>
                        <div class="title d-flex justify-content-center justify-content-md-start align-items-center mb-3 flex-wrap">
                            <i class="fas fa-glass-martini-alt fa-2x mr-3 text-aqua"></i>
                            <h2 id="place_name" class="font-weight-bold"></h2>
                            <!-- <span class="ml-3"><a href="#" class="btn btn-warning font-weight-bold rounded-pill dificultad"><small>Fácil</small></a></span> -->
                        </div>
                        <div class="d-flex">
                            <!-- VALORACION -->
                            <div class="valoration mr-2 d-flex align-items-center">
                                <i class="fas fa-star mr-1 text-warning"></i>
                                <i class="fas fa-star mr-1 text-warning"></i>
                                <i class="fas fa-star mr-1 text-warning"></i>
                                <i class="fas fa-star mr-1 text-warning"></i>
                                <i class="fas fa-star mr-1 text-warning"></i>
                            </div>
                            <!-- PRECIO -->
                            <!-- <div class="precio text-main ml-4">
                                <strong>Precio: </strong> $$-$$$
                            </div> -->
                        </div>
                        <div class="save d-flex justify-content-end align-items-center">
                            <div class="col-md-3 d-flex justify-content-end align-items-center">
                                <button type="button" data-entity="coast" data-id="9999"class="btn btn-outline-dark rounded-pill btn-block btn_guardar_a_mi_viaje text-secondary"><i class="far fa-bookmark mr-3"></i>{{__('language.add_favorites')}}</button>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6 col-md-4 destino mb-2">
                        <div class="destino_img position-relative">
                            <a href="#">
                                <div class="bg_img" style="height:225px; background-image: url('')"></div>
                            </a>
                            <div class="bg_img" style="height:170px; background-image: url('assets/images/senderos/sendero.jpg')"></div>
                            <img src="#" alt="Sendero" class="img-fluid">
                            <a class="destino_info position-absolute p-3 w-100" href="#">
                                <h4 class="ciudad text-white font-weight-bold mb-0">Barcelona</h4>
                                <div class="text-white"><small><span class="calificacion mr-1"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>56 Opiniones</small></div>
                            </a>
                        </div>
                    </div> -->
                    <!-- GALERIA -->
                    <div id="galeria_viaje" class="col-12 mt-3 mb-5">
                        <div class="row no-gutters h-100">
                            <div id="first_img" class="col-6 h-100 bg_img">
                                <a href="" data-fancybox="gallery">
                                    <img src="" alt="" class=""/>
                                </a>
                            </div>
                            <div class="col-6 h-100">
                                <div class="row no-gutters h-50 mb-1 ml-1">
                                    <div id="second_img" class="col mr-1 h-100 bg_img">
                                        <a href="" data-fancybox="gallery">
                                            <img src="" alt="" class=""/>
                                        </a>
                                    </div>
                                    <div id="third_img" class="col-8 h-100 bg_img">
                                        <a href="" data-fancybox="gallery">
                                            <img src="" alt="" class=""/>
                                        </a>
                                    </div>
                                </div>
                                <div class="row no-gutters h-50 ml-1">
                                    <div id="fourth_img" class="col mr-1 h-100 bg_img">
                                        <a href="" data-fancybox="gallery">
                                            <img src="" alt="" class=""/>
                                        </a>
                                    </div>
                                    <div id="fifth_img" class="col h-100 bg_img">
                                        <a href="" data-fancybox="gallery">
                                            <img src="" alt="" class=""/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="viaje_info" class="col-12">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="row">
                                    <!-- informacion playa -->
                                    <div class="col-12 info mb-4">
                                        <h6 class="font-weight-bold mb-4">{{ __('language.information') }}</h6>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <!-- <p class="text-secondary"><i class="fas fa-phone mr-1 text-aqua"></i> <?php //echo $coast['phonenumber']?></p> -->
                                                <div class="text-secondary d-flex">
                                                <i class="fas fa-map-marker-alt mr-2 text-aqua"></i>
                                                <p id="place_address" class="mb-0"></p>
                                                </div><br>
                                                <!-- <div class="row">
                                                    <div class="col-12">
                                                        <button type="button" class="btn btn-link btn-sm font-weight-bold text-secondary mr-4">Visitar web</button>
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="col-lg-12">
                                                <p id="place_short_description" class="text-secondary"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- pendientes -->
                                    <div class="col-12">
                                        <h6 class="font-weight-bold mb-4">{{ __('language.location') }}</h6>
                                        <div id="map_destino"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <!-- <div class="row">
                                    <div class="col-12 info mb-4">
                                        <h6 class="font-weight-bold mb-4">Información extra</h6>
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="col-12 info mb-5">
                                        <h6 class="font-weight-bold mb-4">{{ __('language.type') }}</h6>
                                        <div class="row">
                                            <div class="col-lg-12">
                                            <div class="d-flex">
                                                <i class="fas fa-glass-martini-alt fa-2x mr-3 text-aqua"></i><p id="place_type" class="text-secondary mb-0 align-self-center"></p>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- opiniones -->
                            <div class="col-lg-8 valorations">
                                <h6 class="font-weight-bold mt-4 mb-4">{{ __('language.opinions') }}</h6>
                                <div class="row">
                                    <div class="col-12">
                                        <form id="comment_form">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" id="mi_opinion_titulo" placeholder="{{ Lang::get('language.describe_your_experience') }}" name="title">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" id="mi_opinion" rows="3" placeholder="{{ Lang::get('language.share_all_the_details') }}" name="content"></textarea>
                                            </div>
                                            <div class="form-group">
                                                {!! NoCaptcha::displaySubmit('CommentForm', Lang::get('language.comment'), ['class' => 'btn btn-main p-2']) !!}
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                            <div class="col-12 d-flex justify-content-center">
                                <h3 class="font-weight-bold mb-4 text-dark">Relacionados</h3>
                            </div>
                        </div>
                        <!-- destinos -->
                        <div class="destinos">
                            <div class="row">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection

    @section('script')

    @endsection
