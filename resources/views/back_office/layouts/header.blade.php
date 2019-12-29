    <!DOCTYPE html>
    <html lang="{{ app()->getLocale() }}">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">

            @if (isset($data['title']))
                <title>{{ $data['title'] }}</title>
            @else
                <title>Travelestt</title>
            @endif

            <meta name="csrf-token" content="{{ csrf_token() }}">

            <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

            <!--STYLESHEET-->
            <!--=================================================-->

            <!--Open Sans Font [ OPTIONAL ]-->
            <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'> -->
            <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700" rel="stylesheet">

            <!--Bootstrap Stylesheet [ REQUIRED ]-->
            <link href="{{ asset('assets/css/back_office/bootstrap.min.css') }}" rel="stylesheet">

            <!--Nifty Stylesheet [ REQUIRED ]-->
            <link href="{{ asset('assets/css/back_office/nifty.min.css') }}" rel="stylesheet">

            <!--Nifty Premium Icon [ DEMONSTRATION ]-->
            <link href="{{ asset('assets/css/back_office/demo/nifty-demo-icons.min.css') }}" rel="stylesheet">
            <!-- <link href="css/demo/nifty-demo-icons.min.css" rel="stylesheet"> -->

            <!-- Themify icons -->
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/themify-icons/themify-icons.min.css') }}">

            <!-- Custom styles -->
            <link href="{{ asset('assets/css/back_office/estilos.css') }}" rel="stylesheet">

            <!--=================================================-->

            <!--Pace - Page Load Progress Par [OPTIONAL]-->
            <link href="{{ asset('assets/plugins/pace/pace.min.css')}}" rel="stylesheet">
            <script src="{{ asset('assets/plugins/pace/pace.min.js')}}"></script>

            <!--Demo [ DEMONSTRATION ]-->
            <!-- <link href="css/demo/nifty-demo.min.css" rel="stylesheet"> -->

            <!--Custom scheme [ OPTIONAL ]-->
            <!-- <link href="css/themes/type-c/theme-navy.min.css" rel="stylesheet"> -->

            <!-- Leaflet -->
            @if (isset($data['leaflet']))
                <link rel="stylesheet" href="{{ asset('assets/css/leaflet.css') }}"/>
                <link rel="stylesheet" href="{{ asset('assets/css/leaflet-routing-machine.css') }}"/>
            @endif

            <!--Custom Font-->
			@if (isset($data['css']))
                @foreach ($data['css'] as $file)
                    <link rel="stylesheet" type="text/css" href="{{ asset($file.'?code='.rand(0,9999)) }}">
                @endforeach
            @endif

            <!--=================================================

            REQUIRED
            You must include this in your project.

            RECOMMENDED
            This category must be included but you may modify which plugins or components which should be included in your project.

            OPTIONAL
            Optional plugins. You may choose whether to include it in your project or not.

            DEMONSTRATION
            This is to be removed, used for demonstration purposes only. This category must not be included in your project.

            SAMPLE
            Some script samples which explain how to initialize plugins or components. This category should not be included in your project.

            Detailed information and more samples can be found in the document.

            =================================================-->

        </head>

        <!--TIPS-->
        <!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
        <body>
            <div id="container" class="effect aside-float aside-bright mainnav-lg page-fixedbar">

            <!--NAVBAR-->
            @include('back_office.layouts.navbar')
            <!--END NAVBAR-->

            <div class="boxed">

                <!--CONTENT CONTAINER-->
                <!--===================================================-->
                <div id="content-container">
                    <div id="page-head">

                        <!--Page Title-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <div id="page-title">
                            @if (isset($data['header']))
                                <h1 class="page-header text-overflow">{{ $data['header'] }}</h1>
                            @else
                                <h1 class="page-header text-overflow">Travelestt</h1>
                            @endif
                            
                        </div>
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End page title-->

                        <!--Breadcrumb-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!-- <ol class="breadcrumb">
                                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
                                    <li class="active">Dashboard 2</li>
                        </ol> -->
                        @if (isset($breadcrumb))
                            <ol class="breadcrumb">
                                <li><a href="#"><i class="demo-pli-home"></i></a></li>
                                @foreach ($breadcrumb as $anchor => $url)
                                    @if (!empty($ur))
                                        <li><a href="#"><?= $anchor ?></a></li>
                                    @else
                                        <li class="active"><?= $anchor ?></li>
                                    @endif
                                @endforeach
                            </ol>
                        @endif
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End breadcrumb-->

                    </div>

                    <!--Fixedbar-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!-- <div class="page-fixedbar-container">
                        <div class="page-fixedbar-content">
                            <div class="nano">
                                <div class="nano-content">

                                    <div class="pad-all">
                                        <span class="pad-ver text-main text-sm text-uppercase text-bold">Total ingresos</span>
                                        <p class="text-sm">Diciembre 17, 2017</p>
                                        <p class="text-2x text-main">$798.54</p> -->
                                        <!-- <button data-target="#demo-default-modal" data-toggle="modal" class="btn btn-primary btn-lg">Launch demo modal</button> -->
                                        <!-- <button class="btn btn-block btn-info mar-top" data-target="#new-route-museum-modal" data-toggle="modal">Nuevo destino</button>
                                    </div>
                                    <hr class="new-section-xs">
                                    <div class="pad-hor">
                                        <p class="pad-ver text-main text-sm text-uppercase text-bold">Available Items</p>
                                        <p>Get <strong class="text-main">$5.00</strong> off your next bill by making sure your full payment reaches us before August 5, 2018.</p>
                                    </div>

                                    <div class="pad-all"> -->
                                        <!--Progress bars with labels-->
                                        <!--===================================================-->
                                        <!-- <div class="progress">
                                            <div style="width: 40%;" class="progress-bar progress-bar-success">40%</div>
                                        </div>
                                        <div class="progress">
                                            <div style="width: 75%;" class="progress-bar progress-bar-warning">75%</div>
                                        </div>
                                    </div> -->
                                    <!--===================================================-->
                                    <!-- <ul class="list-unstyled text-center pad-btm mar-no row">
                                        <li class="col-xs-6">
                                            <span class="text-2x text-normal text-main">1,345</span>
                                            <p class="text-muted mar-no">Item Stock</p>
                                        </li>
                                        <li class="col-xs-6">
                                            <span class="text-2x text-normal text-main">278</span>
                                            <p class="text-muted mar-no">Sold Out</p>
                                        </li>
                                    </ul> -->

                                    <!-- <hr class="new-section-xs">

                                    <p class="pad-all text-main text-sm text-uppercase text-bold">Additional Actions</p> -->

                                    <!--Simple Menu-->
                                    <!-- <div class="list-group bg-trans">
                                        <a href="#" class="list-group-item"><i class="demo-pli-information icon-lg icon-fw"></i> Service Information</a>
                                        <a href="#" class="list-group-item"><i class="demo-pli-mine icon-lg icon-fw"></i> Usage Profile</a>
                                        <a href="#" class="list-group-item"><span class="label label-info pull-right">New</span><i class="demo-pli-credit-card-2 icon-lg icon-fw"></i> Payment Options</a>
                                        <a href="#" class="list-group-item"><i class="demo-pli-support icon-lg icon-fw"></i> Message Center</a>
                                    </div>

                                    <ul class="list-group pad-btm bg-trans">
                                        <li class="list-header">
                                            <p class="text-main text-sm text-uppercase text-bold mar-no">Public Settings</p>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="toggle-switch" id="demo-switch-4" type="checkbox" checked>
                                                <label for="demo-switch-4"></label>
                                            </div>
                                            Online status
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="toggle-switch" id="demo-switch-5" type="checkbox" checked>
                                                <label for="demo-switch-5"></label>
                                            </div>
                                            Show offline contact
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="toggle-switch" id="demo-switch-6" type="checkbox" checked>
                                                <label for="demo-switch-6"></label>
                                            </div>
                                            Show my device icon
                                        </li>
                                    </ul>

                                    <hr>
                                    <p class="pad-all text-main text-sm text-uppercase text-bold">Account Settings</p>
                                    <ul class="list-group bg-trans">
                                        <li class="pad-top list-header">
                                            <p class="text-main text-sm text-uppercase text-bold mar-no">Account Settings</p>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="toggle-switch" id="demo-switch-1" type="checkbox" checked>
                                                <label for="demo-switch-1"></label>
                                            </div>
                                            <p class="mar-no text-main">Show my personal status</p>
                                            <small class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</small>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="toggle-switch" id="demo-switch-2" type="checkbox" checked>
                                                <label for="demo-switch-2"></label>
                                            </div>
                                            <p class="mar-no text-main">Show offline contact</p>
                                            <small class="text-muted">Aenean commodo ligula eget dolor. Aenean massa.</small>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="toggle-switch" id="demo-switch-3" type="checkbox">
                                                <label for="demo-switch-3"></label>
                                            </div>
                                            <p class="mar-no text-main">Invisible mode </p>
                                            <small class="text-muted">Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. </small>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End Fixedbar-->

                    <!--Page content-->
                    <!--===================================================-->
                    <div id="page-content">
