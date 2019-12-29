<!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Travelestt</title>

        <!--STYLESHEET-->
        <!--=================================================-->

        <!--Poppins Font [ OPTIONAL ]-->
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700" rel="stylesheet">
        <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'> -->

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

        <!--Bootstrap Stylesheet [ REQUIRED ]-->
        <link href="{{ asset('assets/css/back_office/bootstrap.min.css') }}" rel="stylesheet">

        <!--Nifty Stylesheet [ REQUIRED ]-->
        <link href="{{ asset('assets/css/back_office/nifty.min.css') }}" rel="stylesheet">

        <!--Nifty Premium Icon [ DEMONSTRATION ]-->
        {{-- <link href="css/demo/nifty-demo-icons.min.css" rel="stylesheet"> --}}

        <!--=================================================-->

        <!--Pace - Page Load Progress Par [OPTIONAL]-->
        <link href="{{ asset('assets/plugins/pace/pace.min.css') }}" rel="stylesheet">
        <script src="{{ asset('assets/plugins/pace/pace.min.js') }}"></script>

        <!--Custom styles-->
        @if (isset($data['css']))
            @foreach ($data['css'] as $file)
                <link rel="stylesheet" type="text/css" href="{{ asset($file.'?code='.rand(0,9999)) }}">
            @endforeach
        @endif
        <link href="{{ asset('assets/css/back_office/estilos.css') }}" rel="stylesheet">
        <!--=================================================


        REQUIRED
        You must include this in your project.

        RECOMMENDED
        This category must be included but you may modify which plugins or components which should be included in your project.

        OPTIONAL
        Optional plugins. You may choose whether to include it in your project or not.

        DEMONSTRATION
        This is to be removed, used for demonstration purposes only. This category must not be included in your project.

        SAMPLE
        Some script samples which explain how to initialize plugins or components. This category should not be included in your project.

        Detailed information and more samples can be found in the document.

        =================================================-->

    </head>

    <!--TIPS-->
    <!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->

    <body>
        <div id="container" class="cls-container">

            <!-- BACKGROUND IMAGE -->
            <!--===================================================-->
            <div id="bg-overlay" class="bg-img"></div>
