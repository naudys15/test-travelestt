        <div id="mi_viaje_sidebar" class="px-3">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                    <a href="#" id="nuevo_viaje" class="text-center d-flex flex-column justify-content-center h-100">
                        <img src="{{ asset('assets/images/nuevo_viaje.png') }}" alt="" class="img-fluid align-self-center">
                        <p class="font-weight-bold text-white mt-3">{{ __('language.new_travel') }}</p>
                    </a>
                    </div>
                    @for ($i = 1; $i < 10; $i++)
                        <div class="col-sm-6 viaje mb-3">
                            <a href="#" class="viaje_content">
                            <div class="text-center viaje_img bg_img mb-1 rounded" style="background-image:url({{ asset('assets/images/senderos/sendero.jpg') }})">
                                </div>
                                <p class="font-weight-bold mb-0 text-white">Mi viaje a Alcoy</p>
                                <small class="text-light">Días 3 - Lugares 15</small>
                            </a>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        