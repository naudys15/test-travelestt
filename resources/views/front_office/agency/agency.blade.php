@extends('front_office.layouts.template')

@section('content')
<section id="agencia_hero" class="margin_header">
    <div id="agencia_hero_content" class="h-100">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-12 align-self-center text-center">
                    <div class="btn btn-main btn-lg">
                        Registrate
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<section class="py-5 text-center text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 mb-3">
                <h2 class="font-weight-bold text-dark mb-3">Por que trabajar con nosotros?</h2>
                <p class="text-secondary">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptate sapiente aliquam ea impedit tempore, facere quidem aut! Voluptates.</p>
            </div>
            <div class="col-lg-4">
                <div class="content bg-main py-5 px-3">
                    <div class="icon mb-3"><i class="fas fa-percent"></i></div>
                    <p><strong>Descontamos la comision</strong> del precio de venta</p>                
                </div>
            </div>
            <div class="col-lg-4">
                <div class="content bg-main py-5 px-3">
                    <div class="icon mb-3"><i class="fas fa-flag"></i></div>
                    <p><strong>Mas de 20.000 actividades</strong> (6000 de ellas en espanol)</p>                
                </div>
            </div>
            <div class="col-lg-4">
                <div class="content bg-main py-5 px-3">
                    <div class="icon mb-3"><i class="fas fa-flag"></i></div>
                    <p><strong>Mas de 20.000 actividades</strong> (6000 de ellas en espanol)</p>                
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-light py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 offset-lg-2 mb-5">
                <h2 class="text-dark font-weight-bold">Ya trabajan con nosotros</h2>
            </div>
            @for($i=1;$i<=12;$i++)
            <div class="col-lg-3 cliente mb-4">
                <img src="{{asset('assets/images/logo_azul.png')}}" alt="" class="img-fluid">
            </div>
            @endfor
        </div>
    </div>

</section>

<section class="bg-main text-center text-white py-5">
    <h2 class="mb-3 font-weight-bold">Llena el viaje de tus clientes!</h2>
    <a href="" class="btn btn-light text-main">Registrate</a>
</section>
@endsection

@section('script')

@endsection