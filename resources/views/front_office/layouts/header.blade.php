    <!DOCTYPE html>
		<html lang="{{ app()->getLocale() }}">
			<head>
				<!-- Leaflet -->
				@if (isset($data['leaflet']))
					<link rel="stylesheet" href="{{ asset('assets/css/leaflet.css') }}"/>
					<link rel="stylesheet" href="{{ asset('assets/css/leaflet-routing-machine.css') }}"/>
				@endif

				<!-- Required meta tags -->
				<meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                
                <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

				<!-- Fonts -->
				<link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700" rel="stylesheet">

				<!-- Bootstrap -->
				<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

                {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> --}}

				<!-- Font Awesome -->
				<script src="https://kit.fontawesome.com/d86429a8e8.js" crossorigin="anonymous"></script>
				{{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous"> --}}

				<!-- Main Styles -->
				<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

				<!--Custom Font-->
				@if (isset($data['css']))
					@foreach ($data['css'] as $file)
						<link rel="stylesheet" type="text/css" href="{{ asset($file.'?code='.rand(0,9999)) }}">
					@endforeach
                @endif

                {!! NoCaptcha::renderJs() !!}
                
				<title>Travelestt</title>
			</head>
			<body>
				<header class="fixed-top">
					<!-- <div class="navbar-top">
						<div class="container-fluid h-100">
							<div class="row h-100 align-items-center">
								<div class="col-12">
									<div class="d-flex justify-content-end align-items-center text-white">
										<i class="fab fa-instagram"></i>
										<i class="fab fa-twitter mx-5"></i>
										<i class="fab fa-facebook-f"></i>
									</div>
								</div>
							</div>
						</div>
					</div> -->
					<nav class="navbar navbar-expand-md navbar-light py-3">
						<div class="container position-relative">
							<a class="navbar-brand" href="{{ url(app()->getLocale()) }}"><img class="img-fluid" src="{{ asset('assets/images/logo_blanco.png') }}" alt="Logo"></a>

							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_menu" aria-controls="main_menu" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>
							<div class="collapse navbar-collapse" id="main_menu">
								<div class="dropdown ml-auto">
                                    @if(app()->getLocale() == 'es')
                                        <button class="btn dropdown-toggle" type="button" id="language-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('language.es') }}
                                        </button>
                                    @elseif(app()->getLocale() == 'en')
                                        <button class="btn dropdown-toggle" type="button" id="language-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('language.en') }}
                                        </button>
                                    @elseif(app()->getLocale() == 'fr')
                                        <button class="btn dropdown-toggle" type="button" id="language-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('language.fr') }}
                                        </button>
                                    @endif
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="language-btn">
                                        <?php
                                            $available_languages = Config::get('app.available_language');
                                            $current_language = __('routes');
                                            $segment_2 = Request::segment(2);
                                            $segment_3 = Request::segment(3);
                                            $segment_4 = Request::segment(4);
                                            if (!empty($segment_3)) {
                                                foreach ($current_language as $key => $value) {
                                                    if ($segment_3 == $value) {
                                                        $key_for_translation = $key;
                                                    }
                                                }
                                            }
                                            foreach ($available_languages as $lang) {
                                                if (isset($key_for_translation)) {
                                                    if ($lang != app()->getLocale()) {
                                                        if (empty($segment_3) && empty($segment_4)) {
                                                            $language = __("routes.$key_for_translation", [], $lang);
                                                            ?>
                                                            <a class="dropdown-item" href="{{ url($lang.'/'.$language) }}">{{ __("language.$lang") }}</a>
                                                            <?php
                                                        } else if (!empty($segment_3) && empty($segment_4)) {
                                                            $language = __("routes.$key_for_translation", [], $lang);
                                                            ?>
                                                            <a class="dropdown-item" href="{{ url($lang.'/'.$segment_2.'/'.$language) }}">{{ __("language.$lang") }}</a>
                                                            <?php
                                                        } else {
                                                            $language = __("routes.$key_for_translation", [], $lang);
                                                            ?>
                                                            <a class="dropdown-item" href="{{ url($lang.'/'.$segment_2.'/'.$language.'/'.$segment_4) }}">{{ __("language.$lang") }}</a>
                                                            <?php
                                                        }
                                                    }
                                                } else {
                                                    if ($lang != app()->getLocale()) {
                                                        ?>
                                                        <a class="dropdown-item" href="{{ url($lang.'/'.$segment_2) }}">{{ __("language.$lang") }}</a>
                                                        <?php
                                                    }
                                                }
                                            }
                                        ?>
                                    </div>
								</div>
								<ul class="navbar-nav">
									<li class="nav-item">
											<a id="search_icon" class="nav-link" href="#"> <i class="fas fa-search mr-3"></i></a>
									</li>
									<li class="nav-item">
											<a id="mis_viajes" class="nav-link" href="#"> <i class="fas fa-briefcase mr-3"></i></a>
									</li>
									<li class="nav-item">
											<a id="login_icon" class="nav-link" href="#"> <i class="fas fa-user"></i></a>
									</li>
								</ul>
							</div>
							<div id="login" class="position-absolute px-3 py-4">
								<div class="cls-content">
									<div class="cls-content-sm panel">
										<div class="panel-body">
												<div class="mar-ver pad-btm">
														<h4 class="font-weight-bold text-dark">{{ __('language.enter') }}</h4>
														<p class="text-secondary">{{ __('language.enter_for_continue') }}</p>
												</div>
												<form id="form_login" action="#" method="POST" autocomplete="off">
													@csrf
													<div class="form-group">
														<input type="email" class="form-control" placeholder="{{ __('language.email') }}" name="email" id="email" autofocus>
													</div>
													<div class="form-group">
														<input type="password" class="form-control"  placeholder="{{ __('language.password') }}" name="password" id="password">
													</div>

													<button class="btn btn-main btn-block" id="btn-login" type="button">
														{{ __('language.log_in') }}
													</button>
												</form>
                                 					
										</div>

										<div class="mt-3">
											<div class="">
												<a href="{{ url(app()->getLocale().'/'.__('routes.recovery_password')) }}" class="btn-link text-secondary"><small>{{ __('language.forgot_your_password') }}</small></a>
											</div>

											<div class="d-flex mt-3 justify-content-between">
												<a href="#" class="btn btn-outline-primary mr-1">{{ __('language.sign_up_with') }} <i class="fab fa-facebook-f icon-lg"></i></a>
												<a href="#" class="btn btn-outline-danger ml-1">{{ __('language.sign_up_with') }} <i class="fab fa-google icon-lg"></i></a>
											</div>

											<div class="text-center mt-3 text-secondary">
												{{ __('language.have_an_account') }} <a href="{{ url(app()->getLocale().'/'.__('routes.register')) }}" class="btn-link"><span class="text-info">{{ __('language.sign_up') }}</span></a>
											</div>
										</div>
									</div>
                                    <div id="loading_modal" class="modal fade in" tabindex="-1" data-backdrop="static">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content modal-backdrop">
                                                <div class="modal-body">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</div>
							</div>
							<div id="submenu" class="position-absolute px-3 py-4">
								<div class="cls-content d-flex justify-content-center">
	                                <a id="logout" style="text-decoration: none;" href="#">{{ __('language.logout') }}</a>
								</div>
							</div>

							<!-- <ul class="navbar-nav ml-auto">
								<li class="nav-item">
									<a class="nav-link" href="#"><?php //echo _l('routes'); ?></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#"><?php //echo _l('restaurants'); ?></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#"><?php //echo _l('hotels'); ?></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#"><?php //echo _l('museums'); ?></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#"><?php //echo _l('events'); ?></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#"><?php //echo _l('coasts'); ?></a>
								</li>
								<li class="nav-item">
										<a id="mis_viajes" class="nav-link" href="#"> <i class="fas fa-briefcase mr-3"></i></a>
								</li>
								<li class="nav-item">
										<a class="nav-link" href="<?php //echo base_url(setUrls('login_view')) ?>"> <i class="fas fa-user"></i></a>
								</li>
							</ul> -->
						</div>
					</nav>
				</header>
				@include('front_office.layouts.my_trips_sidebar')
