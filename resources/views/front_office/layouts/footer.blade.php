        <footer class="py-5">
            <div class="container">
                <div class="row">
                    <!-- datos contacto -->
                    <div class="col mb-3">
                        <h5 class="font-weight-bold">Travelestt</h5>
                        <ul class="mt-4">
                            <li><a href="{{ url(app()->getLocale().'/'.__('routes.about_us')) }}" class="text-reset text-decoration-none">{{ __('language.about_us') }}</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">{{ __('language.destinations') }}</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">{{ __('language.travel_guides') }}</a></li>
                            <!-- <li>Guias de viajes</li> -->
                        </ul>
                    </div>

                    <!-- datos servicios -->
                    <div class="col mb-3">
                        <h5 class="font-weight-bold">{{ __('language.work_with_us') }}</h5>
                        <ul class="mt-4">
                            <li><a href="#" class="text-reset text-decoration-none">{{ __('language.suppliers') }}</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">{{ __('language.affiliates')}}</a></li>
                            <li><a href="{{ url(app()->getLocale().'/'.__('routes.register_agency')) }}" class="text-reset text-decoration-none">{{ __('language.travel_agency') }}</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">{{ __('language.job') }}</a></li>
                        </ul>
                    </div>

                    <!-- datos informacion -->
                    <div class="col mb-3">
                        <h5 class="font-weight-bold">{{ __('language.information') }}</h5>
                        <ul class="mt-4">
                            <li><a href="{{ url(app()->getLocale().'/'.__('routes.use_conditions')) }}" class="text-reset text-decoration-none">{{ __('language.general_conditions') }}</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">{{ __('language.legal_warning') }}</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">{{ __('language.privacy_policy') }}</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">{{ __('language.cookie_policy') }}</a></li>
                        </ul>
                    </div>

                    <!-- redes sociales -->
                    <div class="col mb-3">
                        <div class="d-flex justify-content-around align-items-center">
                            <i class="fab fa-instagram"></i>
                            <i class="fab fa-twitter"></i>
                            <i class="fab fa-facebook-f"></i>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- jQuery -->
        <script src="{{ asset('assets/js/jquery.js') }}"></script>
        <!-- Popper -->
        <script src="{{ asset('assets/js/popper.min.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script> -->
        @if (isset($data['leaflet']))
            <script src="{{ asset('assets/js/leaflet.js') }}"></script>
            <script src="{{ asset('assets/js/leaflet-routing-machine.min.js') }}"></script>
        @endif
        @if (isset($data['js']))
            @foreach ($data['js'] as $file)
                <script src="{{ asset($file.'?code='.rand(0,9999)) }}"></script>
            @endforeach
        @endif
    </body>
</html>
