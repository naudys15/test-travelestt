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
                        <i class="fas fa-street-view fa-2x mr-3 text-aqua"></i>
                        <h2 id="place_name" class="font-weight-bold"></h2>
                        <!-- <span class="ml-3"><a href="#" class="btn btn-warning font-weight-bold rounded-pill dificultad"><small>Fácil</small></a></span> -->
                    </div>
                    <div class="d-flex">
                        <!-- VALORACION -->
                        <div class="valoracion mr-2 d-flex align-items-center">
                            <i class="fas fa-star mr-1 text-warning"></i>
                            <i class="fas fa-star mr-1 text-warning"></i>
                            <i class="fas fa-star mr-1 text-warning"></i>
                            <i class="fas fa-star mr-1 text-warning"></i>
                            <i class="fas fa-star mr-1 text-warning"></i>
                            <div class="text-secondary ml-1"><small>56 opiniones</small></div>
                        </div>
                        <!-- IDIOMA -->
                        <div class="precio text-main ml-4">
                            <strong>Idioma: </strong> <span>Español</span>
                        </div>
                        <!-- DURACION -->
					    <div class="horario text-main ml-4">
                            <strong>Duración: </strong> <span>15 - 16h</span>
                        </div>
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
                                <!-- informacion de la visita guiada -->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">{{ __('language.information') }}</h6>
                                    <p id="place_short_description" class="text-secondary">
                                            Disfruta de la cocina moderna y creativa con menú diario en el restaurante Arrels, un lugar muy acogedor lleno de sabores comunes pero cocinados de manera que te sorprenderá.
                                    </p>
                                </div>
                                <!-- itinerario-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Itinerario</h6>
                                    <div id="itinerario" class="text-secondary">
                                        <p id="">
                                            Disfruta de la cocina moderna y creativa con menú diario en el restaurante Arrels, un lugar muy acogedor lleno de   sabores comunes pero cocinados de manera que te sorprenderá.
                                        </p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est pariatur voluptatem dolorum debitis quis esse facilis, eum aperiam, impedit, officia ratione unde voluptas repellat incidunt. Aliquam perferendis quis beatae ut.</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est pariatur voluptatem dolorum debitis quis esse facilis, eum aperiam, impedit, officia ratione unde voluptas repellat incidunt. Aliquam perferendis quis beatae ut.</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est pariatur voluptatem dolorum debitis quis esse facilis, eum aperiam, impedit, officia ratione unde voluptas repellat incidunt. Aliquam perferendis quis beatae ut.</p>
                                    </div>
                                </div>
                                <!-- recogida-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Recogida</h6>
                                    <div id="recogida" class="text-secondary">
                                        <p id="">
                                            Estos son los puntos de recogida:
                                        </p>
                                        <ul>
                                            <li>5:50 - Hotel Wellington (871 7th Ave)</li>
                                            <li>6:00 - Hotel Riu (305 W 46 St)</li>
                                            <li>6:10 - Hotel Pennsylvania (401 7th Ave)</li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- excursion privada-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Excursión privada</h6>
                                    <p id="recogida" class="text-secondary">
                                        Si lo preferís, también podéis reservar la <a>excursión privada a Washington DC.</a>
                                    </p>
                                </div>
                                <!-- precio-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Precio</h6>
                                    <div id="precio" class="text-secondary">
                                        <div class="row text-center text-md-left">
                                            <div class="col-4 d-flex flex-column flex-md-row">
                                                <i class="fas fa-baby text-main display-4 mr-2"></i>
                                                <div>
                                                    <small id="" class="mb-0">Menores de 2 años</small>
                                                    <p id="precio_menores_dos" class="text-main font-weight-bold">Gratis</p>
                                                </div>
                                            </div>
                                            <div class="col-4 d-flex flex-column flex-md-row">
                                                <i class="fas fa-child text-main display-4 mr-2"></i>
                                                <div>
                                                    <small id="" class="mb-0">Menores de 8 años</small>
                                                    <p id="precio_menores_ocho" class="text-main font-weight-bold">$75</p>
                                                </div>
                                            </div>
                                            <div class="col-4 d-flex flex-column flex-md-row">
                                                <i class="fas fa-male text-main display-4 mr-2"></i>
                                                <div>
                                                    <small id="" class="mb-0">Adultos en general</small>
                                                    <p id="precio_adultos" class="text-main font-weight-bold">$120</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Duracion-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Duración</h6>
                                    <p id="duracion" class="text-secondary">
                                        15 - 16h. 
                                    </p>
                                </div>
                                <!-- Idioma-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Idioma</h6>
                                    <p id="idioma" class="text-secondary">
                                        El tour se realiza exclusivamente en español. 
                                    </p>
                                </div>
                                <!-- Incluido-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Incluido</h6>
                                    <ul id="incluido" class="text-secondary">
                                        <li>Transporte en minibús o autobús con aire acondicionado.</li>
                                        <li>Guía de habla española durante toda la excursión.</li>
                                        <li>Entrada al Museo Nacional del Aire y el Espacio.</li>
                                        <li>Descuento de hasta el 14% sobre el precio oficial.</li>
                                    </ul>
                                </div>
                                <!-- Cuando reservar-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">¿Cuándo reservar?</h6>
                                    <p id="cuando_reservar" class="text-secondary">
                                        Reserva cuanto antes para garantizar la disponibilidad, especialmente en puentes y festivos.
                                        <br>
                                        <br>
                                        Se permiten reservas hasta las 22:00 horas del día anterior (hora de Nueva York) siempre que queden plazas.
                                    </p>
                                </div>
                                <!-- Justificante-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Justificante</h6>
                                    <p id="justificante" class="text-secondary">
                                        Te enviaremos un email con un bono que podrás imprimir o llevar en tu móvil a la actividad.
                                    </p>
                                </div>
                                <!-- Preguntas frecuentes-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Preguntas frecuentes</h6>
                                    <div class="accordion mb-3" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link text-main text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        ¿Podemos llevar carrito de bebé plegable durante la visita?
                                                    </button>
                                                </h2>
                                            </div>
                                        
                                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div class="card-body text-secondary">
                                                    Sí, pero es imprescindible indicarlo en los comentarios al hacer la reserva.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingTwo">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link collapsed text-main text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                            ¿Durante el tiempo libre se puede visitar el Capitolio?
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                                <div class="card-body text-secondary">
                                                    Tendréis aproximadamente una hora y media de tiempo libre, dependiendo del tráfico y la hora de llegada a Washington DC. Las visitas al Capitolio tienen horarios muy restrictivos y es muy complicado coordinarlas durante la excursión.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingThree">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed text-main text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                    Se visita siempre el cementerio de Arlington? 
                                                </button>
                                            </h2>
                                            </div>
                                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                                <div class="card-body text-secondary">
                                                    Sí, la visita al cementerio de Arlington siempre está incluida. En los días de lluvia, recibiréis un poncho impermeable para que podáis hacer la visita.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-secondary">Si aún tienes más dudas puedes consultar nuestra sección de preguntas frecuentes. Si tienes algún problema para reservar o necesitas un servicio diferente, contacta con nosotros.</p>
                                </div>
                                <!-- Accesibilidad-->
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Accesibilidad</h6>
                                    <p id="accesibilidad" class="text-secondary">
                                        No (no es apto para personas de movilidad reducida).
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <!-- <div class="row">
                                <div class="col-12 info mb-4">
                                    <h6 class="font-weight-bold mb-4">Información extra</h6>
                                </div>
                            </div> -->
                            <div class="card">
                                <div class="card-header">
                                    Reserva tu cupo
                                </div>
                                <div class="card-body">
                                    {{-- <h5 class="card-title">Special title treatment</h5> --}}
                                    <h3 class="card-title text-main font-weight-bold h2">$120</h3>
                                    <p class="text-secondary mb-0">Sin sobreprecios ni costes ocultos</p>
                                    <p class="text-secondary">Cancelación gratuita hasta 48 horas antes</p>
                                    <a href="#" class="btn btn-main">Reservar</a>
                                </div>
                            </div>
                            {{-- <h6 class="font-weight-bold mb-4">{{ __('language.location') }}</h6> --}}
                            {{-- <div id="map_destino"></div> --}}
                        </div>

                        <!-- opiniones -->
                        <div class="col-lg-8 opinions">
                            <h6 class="font-weight-bold mt-4 mb-4">{{ __('language.opinions') }}</h6>
                            <div class="row">
                                <div class="col-12 opinion_header d-flex mb-3 align-items-center">
                                    <div class="opinion_img mr-2">
                                        <img src="{{ asset('assets/images/users/user.jpg') }}" class="img-fluid avatar">
                                    </div>
                                    <div class="text-main">
                                        <p class="mb-0">Juan <small class="text-secondary">Hace 2 semanas</small></p>
                                        <span><strong>Vistas alucinante </strong></span>
                                    </div>
                                    <div class="valoracion ml-auto">
                                        <i class="fas fa-star mr-1 text-warning"></i>
                                        <i class="fas fa-star mr-1 text-warning"></i>
                                        <i class="fas fa-star mr-1 text-warning"></i>
                                        <i class="fas fa-star mr-1 text-warning"></i>
                                        <i class="fas fa-star mr-1 text-warning"></i>
                                    </div>
                                </div>
                                <div class="col-12 opinion_body">
                                    <p class="text-secondary">Una bonita ruta que discurre al principio por una zona de umbría para, ya a partir del refugio, pasar a zonas de solana. Una ruta con bastante desnivel y con una senda que en su mayor parte esta cubierta por rocas sueltas que pueden originar algún resbalón o caída si no se tiene cuidado. Una vez arriba magnífica vistas de los valles colidantes, pues estamos en el punto más alto de la zona</p>
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