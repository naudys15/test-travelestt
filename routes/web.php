<?php

use Illuminate\Http\Request;
use Travelestt\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'api/', 'middleware' => ['set.language.api', 'lscache:private;esi=on;max-age=900']], function() {
    //Roles de usuario
    Route::resource('roles', 'Api\Roles',
        ['except' => ['create','edit']
    ]);
    //Agregar permiso
    Route::post('roles/addPermission/{role}', 'Api\Roles@addPermission');
    //Editar permiso
    Route::put('roles/editPermission/{role}', 'Api\Roles@editPermission');
    //Eliminar permiso
    Route::delete('roles/revokePermission/{role}', 'Api\Roles@revokePermission');
    //Módulos del sistema
    Route::resource('modules', 'Api\Modules',
        ['except' => ['create','edit']
    ]);
    //Sub-módulos
    Route::resource('submodules', 'Api\Submodules',
        ['except' => ['create','edit']
    ]);
    //Rangos de los Sub-módulos
    Route::resource('rangesubmodules', 'Api\RangeSubmodules',
        ['except' => ['create','edit']
    ]);
    //Paises
    Route::resource('countries', 'Api\Countries',
        ['except' => ['create','edit']
    ]);
    //Provincias o estados
    //Provincias por país
    Route::get('provinces/country/{country}', 'Api\Provinces@getProvincesByCountry');
    Route::resource('provinces', 'Api\Provinces',
        ['except' => ['create','edit']
    ]);
    //Ciudades
    //Ciudades por país
    Route::get('cities/country/{country}', 'Api\Cities@getCitiesByCountry');
    //Ciudades por provincia
    Route::get('cities/province/{province}', 'Api\Cities@getCitiesByProvince');
    //Ciudades por slug
    Route::get('cities/slug/{slug}', 'Api\Cities@getCityBySlug');
    //Recursos por ciudad
    Route::get('cities/{city}/resources', 'Api\Cities@getResources');
    // Ciudades destacadas
    Route::get('cities/featured', 'Api\Cities@getCitiesFeatured');
    // Ciudades top destinos
    Route::get('cities/top_destinations', 'Api\Cities@getCitiesTopDestinations');
    Route::resource('cities', 'Api\Cities',
        ['except' => ['create', 'edit', 'update']
    ]);
    //Entidades
    Route::resource('entities', 'Api\Entities',
        ['except' => ['create','edit']
    ]);
    //Categorías
    Route::resource('categories', 'Api\Characteristic_categories',
        ['except' => ['create','edit']
    ]);
    //Características de las entidades
    Route::get('characteristic_entities/category/{id}', 'Api\Characteristic_entities@getCharacteristicByCategory');
    Route::get('characteristic_entities/entity/{id}', 'Api\Characteristic_entities@getCharacteristicByEntity');
    Route::resource('characteristic_entities', 'Api\Characteristic_entities',
        ['except' => ['create','edit']
    ]);
    //Playas
    Route::get('coasts/slug/{slug}', 'Api\Coasts@getCoastsBySlug');
    Route::get('coasts/city/{city}', 'Api\Coasts@getCoastsByCity');
    Route::get('coasts/search', 'Api\Coasts@searchCoasts');
    Route::get('coasts/{id}/comments', 'Api\Coasts@fetchComments')->where('id', '[0-9]+');
    Route::resource('coasts', 'Api\Coasts',
        ['except' => ['create','edit', 'update']
    ]);
    //Festivales
    Route::get('festivals/slug/{slug}', 'Api\Festivals@getFestivalsBySlug');
    Route::get('festivals/city/{city}', 'Api\Festivals@getFestivalsByCity');
    Route::get('festivals/search', 'Api\Festivals@searchFestivals');
    Route::get('festivals/{id}/comments', 'Api\Festivals@fetchComments')->where('id', '[0-9]+');
    Route::resource('festivals', 'Api\Festivals',
        ['except' => ['create','edit', 'update']
    ]);
    //Museos
    Route::get('museums/slug/{slug}', 'Api\Museums@getMuseumsBySlug');
    Route::get('museums/city/{city}', 'Api\Museums@getMuseumsByCity');
    Route::get('museums/search', 'Api\Museums@searchMuseums');
    Route::get('museums/{id}/comments', 'Api\Museums@fetchComments')->where('id', '[0-9]+');
    Route::resource('museums', 'Api\Museums',
        ['except' => ['create','edit', 'update']
    ]);
    //Sitios nocturnos
    Route::get('night_spots/slug/{slug}', 'Api\Night_spots@getNightSpotsBySlug');
    Route::get('night_spots/city/{city}', 'Api\Night_spots@getNightSpotsByCity');
    Route::get('night_spots/search', 'Api\Night_spots@searchNightSpots');
    Route::get('night_spots/{id}/comments', 'Api\Night_spots@fetchComments')->where('id', '[0-9]+');
    Route::resource('night_spots', 'Api\Night_spots',
        ['except' => ['create','edit', 'update']
    ]);
    //Puntos de interés
    Route::get('points_of_interest/slug/{slug}', 'Api\Points_of_interest@getPointsOfInterestBySlug');
    Route::get('points_of_interest/city/{city}', 'Api\Points_of_interest@getPointsOfInterestByCity');
    Route::get('points_of_interest/search', 'Api\Points_of_interest@searchPointsOfInterest');
    Route::get('points_of_interest/{id}/comments', 'Api\Points_of_interest@fetchComments')->where('id', '[0-9]+');
    Route::resource('points_of_interest', 'Api\Points_of_interest',
        ['except' => ['create','edit', 'update']
    ]);
    //Mercadillos
    Route::get('street_markets/slug/{slug}', 'Api\Street_markets@getStreetMarketsBySlug');
    Route::get('street_markets/city/{city}', 'Api\Street_markets@getStreetMarketsByCity');
    Route::get('street_markets/search', 'Api\Street_markets@searchStreetMarkets');
    Route::get('street_markets/{id}/comments', 'Api\Street_markets@fetchComments')->where('id', '[0-9]+');
    Route::resource('street_markets', 'Api\Street_markets',
        ['except' => ['create','edit', 'update']
    ]);
    //Rutas
    Route::get('routes/slug/{slug}', 'Api\Routes@getRoutesBySlug');
    Route::get('routes/city/{city}', 'Api\Routes@getRoutesByCity');
    Route::get('routes/search', 'Api\Routes@searchRoutes');
    Route::get('routes/{id}/comments', 'Api\Routes@fetchComments')->where('id', '[0-9]+');
    Route::resource('routes', 'Api\Routes',
        ['except' => ['create','edit', 'update']
    ]);
    //Obtener tokens
    Route::get('getToken', function () {
        return csrf_token();
    });
    //Login
    Route::post('login', 'Api\APILogin@login');
    //Registro
    Route::post('register', 'Api\Users@store');
    Route::post('recovery_password', 'Api\Users@generateKeyForRecoveryPassword');
    Route::post('new_password', 'Api\Users@generateNewPassword');

    //Test envío de emails
    Route::post('email', 'Api\APILogin@testEmail');

    Route::group(['middleware' => 'jwt.auth'], function () {
        //Renovar token
        Route::post('renewToken', 'Api\APILogin@renewToken');
        //Obtener usuario a partir del token
        Route::get('getAuthUser', 'Api\APILogin@getAuthUser');
        //Logout
        Route::post('logout', 'Api\APILogin@logout');
        //Roles de usuario: Protegidas por token
        Route::resource('roles', 'Api\Roles',
            ['except' => ['create','edit', 'show', 'index']
        ]);
        //Módulos del sistema: Protegidas por token
        Route::resource('modules', 'Api\Modules',
            ['except' => ['create','edit', 'show', 'index']
        ]);
        //Sub-módulos: Protegidas por token
        Route::resource('submodules', 'Api\Submodules',
            ['except' => ['create','edit', 'show', 'index']
        ]);
        //Rangos de los Sub-módulos: Protegidas por token
        Route::resource('rangesubmodules', 'Api\RangeSubmodules',
            ['except' => ['create','edit', 'show', 'index']
        ]);
        //Paises: Protegidas por token
        Route::post('countries/update/{id}', 'Api\Countries@update');
        Route::resource('countries', 'Api\Countries',
            ['except' => ['create','edit', 'update', 'show', 'index']
        ]);
        //Provincias o estados: Protegidas por token
        Route::post('provinces/update/{id}', 'Api\Provinces@update');
        Route::resource('provinces', 'Api\Provinces',
            ['except' => ['create','edit', 'update', 'show', 'index']
        ]);
        //Ciudades: Protegidas por token
        Route::post('cities/update/{id}', 'Api\Cities@update');
        Route::post('cities/changeOutstanding/{id}', 'Api\Cities@changeOutstanding');
        Route::post('cities/changeTopDestination/{id}', 'Api\Cities@changeTopDestination');
        Route::resource('cities', 'Api\Cities',
            ['except' => ['create','edit', 'update', 'show', 'index']
        ]);
        //Usuarios: Protegidas por token
        Route::resource('users', 'Api\Users',
            ['except' => ['create','edit', 'store', 'update']
        ]);
        Route::post('users/{id}', 'Api\Users@update');

        //Agregar permiso
        Route::post('users/addPermission/{user}', 'Api\Users@addPermission');
        //Editar permiso
        Route::put('users/editPermission/{user}', 'Api\Users@editPermission');
        //Eliminar permiso
        Route::delete('users/revokePermission/{user}', 'Api\Users@revokePermission');
        //Entidades: Protegidas por token
        Route::resource('entities', 'Api\Entities',
            ['except' => ['create','edit', 'show', 'index']
        ]);
        //Categorías: Protegidas por token
        Route::resource('categories', 'Api\Characteristic_categories',
            ['except' => ['create','edit', 'show', 'index']
        ]);
        //Características de las entidades: Protegidas por token
        Route::resource('characteristic_entities', 'Api\Characteristic_entities',
            ['except' => ['create','edit', 'show', 'index']
        ]);
        //Playas: Protegidas por token
        Route::post('coasts/update/{id}', 'Api\Coasts@update');
        Route::post('coasts/changeStatus/{id}', 'Api\Coasts@changeStatus');
        Route::post('coasts/changeOutstanding/{id}', 'Api\Coasts@changeOutstanding');
        Route::post('coasts/{id}/comment', 'Api\Coasts@storeComment')->where('id', '[0-9]+');
        Route::put('coasts/{id}/comment', 'Api\Coasts@updateComment')->where('id', '[0-9]+');
        Route::delete('coasts/{id}/comment', 'Api\Coasts@destroyComment')->where('id', '[0-9]+');
        Route::resource('coasts', 'Api\Coasts',
            ['except' => ['create','edit', 'update', 'show', 'index']
        ]);
        //Festivales: Protegidas por token
        Route::post('festivals/update/{id}', 'Api\Festivals@update');
        Route::post('festivals/changeStatus/{id}', 'Api\Festivals@changeStatus');
        Route::post('festivals/changeOutstanding/{id}', 'Api\Festivals@changeOutstanding');
        Route::post('festivals/{id}/comment', 'Api\Festivals@storeComment')->where('id', '[0-9]+');
        Route::put('festivals/{id}/comment', 'Api\Festivals@updateComment')->where('id', '[0-9]+');
        Route::delete('festivals/{id}/comment', 'Api\Festivals@destroyComment')->where('id', '[0-9]+');
        Route::resource('festivals', 'Api\Festivals',
            ['except' => ['create','edit', 'update', 'show', 'index']
        ]);
        //Museos: Protegidas por token
        Route::post('museums/update/{id}', 'Api\Museums@update');
        Route::post('museums/changeStatus/{id}', 'Api\Museums@changeStatus');
        Route::post('museums/changeOutstanding/{id}', 'Api\Museums@changeOutstanding');
        Route::post('museums/{id}/comment', 'Api\Museums@storeComment')->where('id', '[0-9]+');
        Route::put('museums/{id}/comment', 'Api\Museums@updateComment')->where('id', '[0-9]+');
        Route::delete('museums/{id}/comment', 'Api\Museums@destroyComment')->where('id', '[0-9]+');
        Route::resource('museums', 'Api\Museums',
            ['except' => ['create','edit', 'update', 'show', 'index']
        ]);
        //Sitios nocturnos: Protegidas por token
        Route::post('night_spots/update/{id}', 'Api\Night_spots@update');
        Route::post('night_spots/changeStatus/{id}', 'Api\Night_spots@changeStatus');
        Route::post('night_spots/changeOutstanding/{id}', 'Api\Night_spots@changeOutstanding');
        Route::post('night_spots/{id}/comment', 'Api\Night_spots@storeComment')->where('id', '[0-9]+');
        Route::put('night_spots/{id}/comment', 'Api\Night_spots@updateComment')->where('id', '[0-9]+');
        Route::delete('night_spots/{id}/comment', 'Api\Night_spots@destroyComment')->where('id', '[0-9]+');
        Route::resource('night_spots', 'Api\Night_spots',
            ['except' => ['create','edit', 'update', 'show', 'index']
        ]);
        //Puntos de interés: Protegidas por token
        Route::post('points_of_interest/update/{id}', 'Api\Points_of_interest@update');
        Route::post('points_of_interest/changeStatus/{id}', 'Api\Points_of_interest@changeStatus');
        Route::post('points_of_interest/changeOutstanding/{id}', 'Api\Points_of_interest@changeOutstanding');
        Route::post('points_of_interest/{id}/comment', 'Api\Points_of_interest@storeComment')->where('id', '[0-9]+');
        Route::put('points_of_interest/{id}/comment', 'Api\Points_of_interest@updateComment')->where('id', '[0-9]+');
        Route::delete('points_of_interest/{id}/comment', 'Api\Points_of_interest@destroyComment')->where('id', '[0-9]+');
        Route::resource('points_of_interest', 'Api\Points_of_interest',
            ['except' => ['create','edit', 'update', 'show', 'index']
        ]);
        //Mercadillos: Protegidas por token
        Route::post('street_markets/update/{id}', 'Api\Street_markets@update');
        Route::post('street_markets/changeStatus/{id}', 'Api\Street_markets@changeStatus');
        Route::post('street_markets/changeOutstanding/{id}', 'Api\Street_markets@changeOutstanding');
        Route::post('street_markets/{id}/comment', 'Api\Street_markets@storeComment')->where('id', '[0-9]+');
        Route::put('street_markets/{id}/comment', 'Api\Street_markets@updateComment')->where('id', '[0-9]+');
        Route::delete('street_markets/{id}/comment', 'Api\Street_markets@destroyComment')->where('id', '[0-9]+');
        Route::resource('street_markets', 'Api\Street_markets',
            ['except' => ['create','edit', 'update', 'show', 'index']
        ]);
        //Rutas: Protegidas por token
        Route::post('routes/update/{id}', 'Api\Routes@update');
        Route::post('routes/changeStatus/{id}', 'Api\Routes@changeStatus');
        Route::post('routes/changeOutstanding/{id}', 'Api\Routes@changeOutstanding');
        Route::post('routes/{id}/comment', 'Api\Routes@storeComment')->where('id', '[0-9]+');
        Route::put('routes/{id}/comment', 'Api\Routes@updateComment')->where('id', '[0-9]+');
        Route::delete('routes/{id}/comment', 'Api\Routes@destroyComment')->where('id', '[0-9]+');
        Route::resource('routes', 'Api\Routes',
            ['except' => ['create','edit', 'update', 'show', 'index']
        ]);
        //Obtener log completo
        Route::get('log', 'Api\Log_activities@index');
        //Obtener log específico
        Route::get('log/{id}', 'Api\Log_activities@show');
        //Obtener log de un usuario
        Route::get('log/user/{id}', 'Api\Log_activities@getLogByUser');
        //Obtener log de un día específico
        Route::get('logs/date', 'Api\Log_activities@getLogBySpecificDate');
        //Obtener log de un rango de fechas
        Route::get('logs/date_range', 'Api\Log_activities@getLogByRangeOfDate');
    });
});

// Ruta generica para redireccionar al dominio en español
Route::get('/', function () {
    return redirect(app()->getLocale());
});

// Middleware para front office relacionada a localizacion del sitio
Route::group([ 'prefix' => '{locale}', 'where' => [ 'locale' => '[a-zA-Z]{2}' ], 'middleware' => ['setlocale', 'lscache:public;esi=on;max-age=900'] ], function() {

    // Middleware para vistas de back office
    Route::group([ 'prefix' => __('routes.panel'), 'middleware' => 'roleuser' ], function () {

        // Ruta base del back office
        Route::get('/', function () {
            $data = array(
                'title'    =>    'Travelestt | '.__('language.panel'),
                'js'       =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                )
            );
            return view('back_office.dashboard', compact('data'));
        });

        // Ruta principal para ver playas
        Route::get('/'.__('routes.coasts'), function () {
            $data = array(
                'css'       =>     array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'        =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/coasts/coasts.js'
                ),
                'permissions' => unserialize(session('permissions'))
            );
            return view('back_office.coasts.coasts', compact('data'));
        });

        // Ruta para ver playas de una ciudad
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.coasts'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/coasts/coasts.js'
                )
            );
            return view('back_office.coasts.coasts', compact('data'));
        });

        // Ruta principal para crear una playa
        Route::get('/'.__('routes.new_coast'), function() {
            $data = array(
                'leaflet'    =>    'true',
                'css'        =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'         =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/coasts/coast.js'
                ),
                'url'               =>    url('api/coasts'),
                'method'            =>    'POST',
            );
            return view('back_office.coasts.coast', compact('data'));
        });

        // Ruta para crear una playa a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.new_coast'), function() {
            $data = array(
                'leaflet'    =>    'true',
                'css'        =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'         =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/coasts/coast.js'
                ),
                'url'               =>    url('api/coasts'),
                'method'            =>    'POST',
            );
            return view('back_office.coasts.coast', compact('data'));
        });

        // Ruta principal para editar una playa
        Route::get('/'.__('routes.edit_coast').'/{id}', function(Request $request, $prefix, $id) {
            $data = array(
                'leaflet'    =>    'true',
                'css'        =>    array(
                  'assets/plugins/dropzone/dropzone.min.css',
                  'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                  'assets/plugins/select2/css/select2.min.css',
                  'assets/plugins/summernote/summernote.min.css',
                  'assets/css/back_office/spinner.css',
                  'assets/css/back_office/jquery-ui.css',
                  'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'         =>    array(
                  'assets/js/index.js',
                  'assets/js/back_office/translations.js',
                  'assets/js/back_office/authorization.js',
                  'assets/plugins/dropzone/dropzone.min.js',
                  'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                  'assets/plugins/select2/js/select2.min.js',
                  'assets/plugins/summernote/summernote.min.js',
                  'assets/plugins/masked-input/jquery.maskedinput.min.js',
                  'assets/js/back_office/jquery-ui.js',
                  'assets/js/back_office/index_place.js',
                  'assets/js/back_office/coasts/coast.js'
                ),
                'url'               =>     url('api/coasts/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.coasts.coast', compact('data'));
        });

        // Ruta para editar una playa a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.edit_coast').'/{id}', function(Request $request, $prefix, $city, $id) {
            $data = array(
                'leaflet'    =>    'true',
                'css'        =>    array(
                  'assets/plugins/dropzone/dropzone.min.css',
                  'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                  'assets/plugins/select2/css/select2.min.css',
                  'assets/plugins/summernote/summernote.min.css',
                  'assets/css/back_office/spinner.css',
                  'assets/css/back_office/jquery-ui.css',
                  'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'          =>    array(
                  'assets/js/index.js',
                  'assets/js/back_office/translations.js',
                  'assets/js/back_office/authorization.js',
                  'assets/plugins/dropzone/dropzone.min.js',
                  'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                  'assets/plugins/select2/js/select2.min.js',
                  'assets/plugins/summernote/summernote.min.js',
                  'assets/plugins/masked-input/jquery.maskedinput.min.js',
                  'assets/js/back_office/jquery-ui.js',
                  'assets/js/back_office/index_place.js',
                  'assets/js/back_office/coasts/coast.js'
                ),
                'url'               =>     url('api/coasts/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.coasts.coast', compact('data'));
        });

        // Ruta principal para ver festivales
        Route::get('/'.__('routes.festivals'), function () {
            $data = array(
                'css'       =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'        =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/festivals/festivals.js'
                )
            );
            return view('back_office.festivals.festivals', compact('data'));
        });

        // Ruta para ver festivales de una ciudad
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.festivals'), function () {
            $data = array(
                'css'       =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'        =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/festivals/festivals.js'
                )
            );
            return view('back_office.festivals.festivals', compact('data'));
        });

        // Ruta principal para crear un festival
        Route::get('/'.__('routes.new_festival'), function() {
            $data = array(
                'leaflet'   =>    'true',
                'css'       =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'        =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/festivals/festival.js',
                ),
                'url'               =>    url('api/festivals'),
                'method'            =>    'POST',
            );
            return view('back_office.festivals.festival', compact('data'));
        });

        // Ruta para crear un festival a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.new_festival'), function() {
            $data = array(
                'leaflet'   =>    'true',
                'css'       =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'        =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/festivals/festival.js',
                ),
                'url'               =>    url('api/festivals'),
                'method'            =>    'POST',
            );
            return view('back_office.festivals.festival', compact('data'));
        });

        // Ruta principal para editar un festival
        Route::get('/'.__('routes.edit_festival').'/{id}', function(Request $request, $prefix, $id) {
            $data = array(
                'leaflet'    =>    'true',
                'css'        =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'         =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/festivals/festival.js',
                ),
                'url'               =>     url('api/festivals/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.festivals.festival', compact('data'));
        });

        // Ruta para editar un festival a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.edit_festival').'/{id}', function(Request $request, $prefix, $city, $id) {
            $data = array(
                'leaflet'    =>    'true',
                'css'        =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'         =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/festivals/festival.js',
                ),
                'url'               =>     url('api/festivals/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.festivals.festival', compact('data'));
        });

        // Ruta principal para ver museos
        Route::get('/'.__('routes.museums'), function () {
            $data = array(
                'css'    =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'    =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/museums/museums.js',
                )
            );
            return view('back_office.museums.museums', compact('data'));
        });

        // Ruta para ver museos de una ciudad
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.museums'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/museums/museums.js',
                )
            );
            return view('back_office.museums.museums', compact('data'));
        });

        // Ruta principal para crear un museo
        Route::get('/'.__('routes.new_museum'), function() {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/museums/museum.js'
                ),
                'url'               =>    url('api/museums'),
                'method'            =>    'POST',
            );
            return view('back_office.museums.museum', compact('data'));
        });

        // Ruta para crear un museo a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.new_museum'), function() {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/museums/museum.js'
                ),
                'url'               =>    url('api/museums'),
                'method'            =>    'POST',
            );
            return view('back_office.museums.museum', compact('data'));
        });

        // Ruta principal para editar un museo
        Route::get('/'.__('routes.edit_museum').'/{id}', function(Request $request, $prefix, $id) {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/museums/museum.js'
                ),
                'url'               =>     url('api/museums/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.museums.museum', compact('data'));
        });

        // Ruta para editar un museo a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.edit_museum').'/{id}', function(Request $request, $prefix, $city, $id) {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/museums/museum.js'
                ),
                'url'               =>     url('api/museums/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.museums.museum', compact('data'));
        });

        // Ruta principal para ver puntos de interes
        Route::get('/'.__('routes.points_of_interest'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/points_of_interest/points_of_interest.js'
                )
            );
            return view('back_office.points_of_interest.points_of_interest', compact('data'));
        });

        // Ruta para ver puntos de interes de una ciudad
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.points_of_interest'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/points_of_interest/points_of_interest.js'
                )
            );
            return view('back_office.points_of_interest.points_of_interest', compact('data'));
        });

        // Ruta principal para crear un punto de interes
        Route::get('/'.__('routes.new_point_of_interest'), function() {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/points_of_interest/point_of_interest.js'
                ),
                'url'               =>    url('api/points_of_interest'),
                'method'            =>    'POST',
            );
            return view('back_office.points_of_interest.point_of_interest', compact('data'));
        });

        // Ruta para crear un punto de interes a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.new_point_of_interest'), function() {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/points_of_interest/point_of_interest.js'
                ),
                'url'               =>    url('api/points_of_interest'),
                'method'            =>    'POST',
            );
            return view('back_office.points_of_interest.point_of_interest', compact('data'));
        });

        // Ruta principal para editar un punto de interes
        Route::get('/'.__('routes.edit_point_of_interest').'/{id}', function(Request $request, $prefix, $id) {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/points_of_interest/point_of_interest.js'
                ),
                'url'               =>     url('api/points_of_interest/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.points_of_interest.point_of_interest', compact('data'));
        });

        // Ruta para editar un punto de interes a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.edit_point_of_interest').'/{id}', function(Request $request, $prefix, $city, $id) {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/points_of_interest/point_of_interest.js'
                ),
                'url'               =>     url('api/points_of_interest/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.points_of_interest.point_of_interest', compact('data'));
        });

        // Ruta principal para ver sitios nocturnos
        Route::get('/'.__('routes.night_spots'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/night_spots/night_spots.js'
                )
            );
            return view('back_office.night_spots.night_spots', compact('data'));
        });

        // Ruta para ver sitios nocturnos de una ciudad
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.night_spots'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/night_spots/night_spots.js'
                )
            );
            return view('back_office.night_spots.night_spots', compact('data'));
        });

        // Ruta principal para crear un sitio nocturno
        Route::get('/'.__('routes.new_night_spot'), function() {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/night_spots/night_spot.js'
                ),
                'url'               =>     url('api/night_spots'),
                'method'            =>    'POST',
            );
            return view('back_office.night_spots.night_spot', compact('data'));
        });

        // Ruta para crear un sitio nocturno a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.new_night_spot'), function() {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/night_spots/night_spot.js'
                ),
                'url'               =>     url('api/night_spots'),
                'method'            =>    'POST',
            );
            return view('back_office.night_spots.night_spot', compact('data'));
        });

        // Ruta principal para editar un sitio nocturno
        Route::get('/'.__('routes.edit_night_spot').'/{id}', function(Request $request, $prefix, $id) {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/night_spots/night_spot.js'
                ),
                'url'               =>     url('api/night_spots/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.night_spots.night_spot', compact('data'));
        });

        // Ruta para editar un sitio nocturno a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.edit_night_spot').'/{id}', function(Request $request, $prefix, $city, $id) {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/night_spots/night_spot.js'
                ),
                'url'               =>     url('api/night_spots/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.night_spots.night_spot', compact('data'));
        });

        // Ruta principal para ver rutas
        Route::get('/'.__('routes.routes'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/routes/routes.js'
                )
            );
            return view('back_office.routes.routes', compact('data'));
        });

        // Ruta para ver rutas de una ciudad
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.routes'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/routes/routes.js'
                )
            );
            return view('back_office.routes.routes', compact('data'));
        });

        // Ruta principal para crear una ruta
        Route::get('/'.__('routes.new_route'), function() {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/routes/route.js'
                ),
                'url'               =>     url('api/routes'),
                'method'            =>    'POST',
            );
            return view('back_office.routes.route', compact('data'));
        });

        // Ruta para crear una ruta a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.new_route'), function() {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/routes/route.js'
                ),
                'url'               =>     url('api/routes'),
                'method'            =>    'POST',
            );
            return view('back_office.routes.route', compact('data'));
        });

        // Ruta principal para editar una ruta
        Route::get('/'.__('routes.edit_route').'/{id}', function(Request $request, $prefix, $id) {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/routes/route.js'
                ),
                'url'               =>     url('api/routes/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.routes.route', compact('data'));
        });

        // Ruta para editar una ruta a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.edit_route').'/{id}', function(Request $request, $prefix, $city, $id) {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/routes/route.js'
                ),
                'url'               =>     url('api/routes/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.routes.route', compact('data'));
        });

        // Ruta principal para ver mercadillos
        Route::get('/'.__('routes.street_markets'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/street_markets/street_markets.js'
                )
            );
            return view('back_office.street_markets.street_markets', compact('data'));
        });

        // Ruta para ver mercadillos de una ciudad
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.street_markets'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/street_markets/street_markets.js'
                )
            );
            return view('back_office.street_markets.street_markets', compact('data'));
        });

        // Ruta principal para crear un mercadillo
        Route::get('/'.__('routes.new_street_market'), function() {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/street_markets/street_market.js'
                ),
                'url'               =>     url('api/street_markets'),
                'method'            =>    'POST',
            );
            return view('back_office.street_markets.street_market', compact('data'));
        });

        // Ruta para crear un mercadillo a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.new_street_market'), function() {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/street_markets/street_market.js'
                ),
                'url'               =>     url('api/street_markets'),
                'method'            =>    'POST',
            );
            return view('back_office.street_markets.street_market', compact('data'));
        });

        // Ruta principal para editar un mercadillo
        Route::get('/'.__('routes.edit_street_market').'/{id}', function(Request $request, $prefix, $id) {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/street_markets/street_market.js'
                ),
                'url'               =>     url('api/street_markets/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.street_markets.street_market', compact('data'));
        });

        // Ruta para editar un mercadillo a una ciudad especifica
        Route::get('/'.__('routes.cities').'/{city}/'.__('routes.edit_street_market').'/{id}', function(Request $request, $prefix, $city, $id) {
            $data = array(
                'leaflet'           =>    'true',
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/street_markets/street_market.js'
                ),
                'url'               =>     url('api/street_markets/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.street_markets.street_market', compact('data'));
        });

        // Ruta para ver paises
        Route::get('/'.__('routes.countries'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/countries/countries.js'
                )
            );
            return view('back_office.countries.countries', compact('data'));
        });

        // Ruta para ver provincias de un pais
        Route::get('/'.__('routes.countries').'/{id}/'.__('routes.provinces'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/provinces/provinces.js'
                )
            );
            return view('back_office.provinces.provinces', compact('data'));
        });

        // Ruta para ver ciudades de un pais
        Route::get('/'.__('routes.countries').'/{id}/'.__('routes.cities'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-select/bootstrap-select.min.css',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/bootstrap-select/bootstrap-select.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/cities/cities.js'
                )
            );
            return view('back_office.cities.cities', compact('data'));
        });

        // Ruta principal para crear un pais
        Route::get('/'.__('routes.new_country'), function() {
            $data = array(
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/countries/country.js'
                ),
                'url'               =>     url('api/countries'),
                'method'            =>    'POST',
            );
            return view('back_office.countries.country', compact('data'));
        });

        // Ruta principal para editar un pais
        Route::get('/'.__('routes.edit_country').'/{id}', function(Request $request, $prefix, $id) {
            $data = array(
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/countries/country.js'
                ),
                'url'               =>     url('api/countries/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.countries.country', compact('data'));
        });

        // Ruta para ver las ciudades de una provincia especifica
        Route::get('/'.__('routes.provinces').'/{id}/'.__('routes.cities'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-select/bootstrap-select.min.css',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/plugins/switchery/switchery.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/plugins/bootstrap-select/bootstrap-select.min.js',
                    'assets/plugins/switchery/switchery.min.js',
                    'assets/js/back_office/table_content.js',
                    'assets/js/back_office/cities/cities.js'
                )
            );
            return view('back_office.cities.cities', compact('data'));
        });

        // Ruta para crear una provincia a un pais especifico
        Route::get('/'.__('routes.countries').'/{id}/'.__('routes.new_province'), function() {
            $data = array(
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/provinces/province.js'
                ),
                'url'               =>     url('api/provinces'),
                'method'            =>    'POST',
            );
            return view('back_office.provinces.province', compact('data'));
        });
    
        // Ruta para ediatr una provincia de un pais especifico
        Route::get('/'.__('routes.countries').'/{id_country}/'.__('routes.edit_province').'/{id}', function(Request $request, $prefix, $id_country, $id) {
            $data = array(
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/provinces/province.js'
                ),
                'url'               =>     url('api/provinces/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.provinces.province', compact('data'));
        });

        // Ruta para crear una ciudad de un pais especifico
        Route::get('/'.__('routes.countries').'/{id}/'.__('routes.new_city'), function() {
            $data = array(
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/cities/city.js'
                ),
                'url'               =>     url('api/cities'),
                'method'            =>    'POST',
            );
            return view('back_office.cities.city', compact('data'));
        });

        // Ruta para editar una provincia de un pais especifico
        Route::get('/'.__('routes.countries').'/{id_country}/'.__('routes.edit_city').'/{id}', function(Request $request, $prefix, $id_country, $id) {
            $data = array(
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/cities/city.js'
                ),
                'url'               =>     url('api/cities/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.cities.city', compact('data'));
        });

        // Ruta para crear una ciudad de una provincia especifica
        Route::get('/'.__('routes.provinces').'/{id}/'.__('routes.new_city'), function() {
            $data = array(
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/cities/city.js'
                ),
                'url'               =>     url('api/cities'),
                'method'            =>    'POST',
            );
            return view('back_office.cities.city', compact('data'));
        });

        // Ruta para editar una ciudad de una provincia especifica
        Route::get('/'.__('routes.provinces').'/{id_province}/'.__('routes.edit_city').'/{id}', function(Request $request, $prefix, $id_province, $id) {
            $data = array(
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
                    'assets/plugins/select2/css/select2.min.css',
                    'assets/plugins/summernote/summernote.min.css',
                    'assets/css/back_office/spinner.css',
                    'assets/css/back_office/jquery-ui.css',
                    'assets/css/back_office/jquery-ui.structure.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
                    'assets/plugins/select2/js/select2.min.js',
                    'assets/plugins/summernote/summernote.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/jquery-ui.js',
                    'assets/js/back_office/index_place.js',
                    'assets/js/back_office/cities/city.js'
                ),
                'url'               =>     url('api/cities/update/'.$id),
                'method'            =>    'POST',
            );
            return view('back_office.cities.city', compact('data'));
        });

        Route::get('/'.__('routes.users'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/js/back_office/users/users.js'
                )
            );
            return view('back_office.users.users', compact('data'));
        });

        Route::get('/'.__('routes.new_user'), function() {
            $data = array(
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/users/user.js'
                ),
                'url'               =>     url('api/register'),
                'method'            =>    'POST',
                'edit'              =>    false,
            );
            return view('back_office.users.user', compact('data'));
        });

        Route::get('/'.__('routes.edit_user').'/{id}', function(Request $request,$prefix,$id) {
            $data = array(
                'css'               =>    array(
                    'assets/plugins/dropzone/dropzone.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/plugins/dropzone/dropzone.min.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/masked-input/jquery.maskedinput.min.js',
                    'assets/js/back_office/users/user.js'
                ),
                'url'               =>     url('api/users/'.$id),
                'method'            =>    'POST',
                'edit'              =>    true,
            );
            return view('back_office.users.user', compact('data'));
        });

        Route::get('/'.__('routes.permissions_user').'/{id}', function(Request $request,$prefix,$id) {
            $data = array(
                'css'               =>    array(
                    'assets/css/back_office/spinner.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/js/back_office/permissions.js',
                    'assets/js/back_office/users/permissions.js'
                ),
            );
            return view('back_office.users.permissions', compact('data'));
        });

        Route::get('/'.__('routes.roles'), function () {
            $data = array(
                'css'              =>    array(
                    'assets/plugins/bootstrap-table/bootstrap-table.min.css',
                    'assets/css/back_office/spinner.css',
                ),
                'js'               =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/plugins/bootstrap-table/bootstrap-table.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-es-ES.min.js',
                    'assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js',
                    'assets/js/back_office/roles/roles.js'
                )
            );
            return view('back_office.roles.roles', compact('data'));
        });

        Route::get('/'.__('routes.new_role'), function() {
            $data = array(
                'css'               =>    array(
                    'assets/css/back_office/spinner.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/js/back_office/roles/role.js'
                ),
                'url'               =>     url('api/roles'),
                'method'            =>    'POST',
            );
            return view('back_office.roles.role', compact('data'));
        });

        Route::get('/'.__('routes.edit_role').'/{id}', function(Request $request,$prefix,$id) {
            $data = array(
                'css'               =>    array(
                    'assets/css/back_office/spinner.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/js/back_office/roles/role.js'
                ),
                'url'               =>     url('api/roles/'.$id),
                'method'            =>    'PUT',
            );
            return view('back_office.roles.role', compact('data'));
        });

        Route::get('/'.__('routes.permissions_role').'/{id}', function(Request $request,$prefix,$id) {
            $data = array(
                'css'               =>    array(
                    'assets/css/back_office/spinner.css',
                ),
                'js'                =>    array(
                    'assets/js/index.js',
                    'assets/js/back_office/translations.js',
                    'assets/js/back_office/authorization.js',
                    'assets/js/back_office/permissions.js',
                    'assets/js/back_office/roles/permissions.js'
                ),
            );
            return view('back_office.roles.permissions', compact('data'));
        });
    });

    Route::get('/', function () {
        $data = array(
            'css'    =>    array(
                'assets/css/owl.carousel.min.css',
                'assets/css/owl.theme.default.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/back_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/index.js',
                'assets/js/owl.carousel.min.js',
            ),
        );
        return view('front_office.home', compact('data'));
    });

    Route::get('/'.__('routes.access_panel'), function () {
        $data = array(
            'css'    =>    array(
                'assets/plugins/select2/css/select2.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/back_office/translations.js',
                'assets/js/helpers.js',
                'assets/js/front_office/users/login_admin.js',
                'assets/plugins/select2/js/select2.min.js',
            ),
        );
        return view('front_office.users.login_admin', compact('data'));
    });

    Route::get('/'.__('routes.about_us'), function () {
        $data = array(
            'css'    =>    array(
                'assets/css/owl.carousel.min.css',
                'assets/css/owl.theme.default.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/owl.carousel.min.js',
            ),
        );
        return view('front_office.sections.about_us', compact('data'));
    });

    Route::get('/'.__('routes.use_conditions'), function () {
        $data = array(
            'css'    =>    array(
                'assets/css/owl.carousel.min.css',
                'assets/css/owl.theme.default.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/owl.carousel.min.js',
            ),
        );
        return view('front_office.sections.use_conditions', compact('data'));
    });

    Route::get('/'.__('routes.register'), function () {
        $data = array(
            'css'    =>    array(
                'assets/plugins/select2/css/select2.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/helpers.js',
                'assets/js/front_office/users/register.js',
                'assets/plugins/select2/js/select2.min.js',
            ),
        );
        return view('front_office.users.register', compact('data'));
    });

    Route::get('/'.__('routes.register_agency'), function () {
        $data = array(
            'css'    =>    array(
                'assets/plugins/select2/css/select2.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/helpers.js',
                'assets/js/front_office/users/register.js',
                'assets/plugins/select2/js/select2.min.js',
            )
        );
        return view('front_office.users.register_agency', compact('data'));
    });

    Route::get('/'.__('routes.agency'), function () {
        $data = array(
            'css'    =>    array(
                // 'assets/plugins/select2/css/select2.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/helpers.js',
                // 'assets/js/front_office/users/register.js',
                // 'assets/plugins/select2/js/select2.min.js',
            )
        );
        return view('front_office.agency.agency', compact('data'));
    });
    Route::get('/'.__('routes.recovery_password'), function () {
        $data = array(
            'css'    =>    array(
                'assets/css/back_office/spinner.css',
            ),
            'js'     =>    array(
                'assets/js/front_office/users/recovery_password.js',
            ),
        );
        return view('front_office.users.recovery_password', compact('data'));
    });

    Route::get('/'.__('routes.new_password'), function (Request $request) {
        if (!$request->key)
            return redirect(app()->getLocale());
        $user = User::where('key', '=', $request->key)->first();
        if (!$user)
            return redirect(app()->getLocale());
        if (Hash::check(date('Y-m-d').$user->id, $user->key)) {
            $data = array(
                'key'   =>    $request->key,
                'css'   =>    array(
                    'assets/css/back_office/spinner.css',
                ),
                'js'    =>    array(

                    'assets/js/front_office/users/new_password.js',
                )
            );
        } else {
            $data = array(
                'error'    =>    __('message.key_defeated'),
                'css'   =>    array(
                    'assets/css/back_office/spinner.css',
                ),
                'js'       =>    array(

                )
            );
        }
        return view('front_office.users.new_password', compact('data'));
    });

    Route::get('/{city}/'.__('routes.coasts'), function (Request $request, $prefix, $city) {
        $data = array(
            'css'    =>    array(
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/front_office/cities/index.js',
                'assets/js/front_office/coasts/coasts.js',
            ),
        );
        return view('front_office.coasts.coasts', compact('data'));
    });

    Route::get('/{city}/'.__('routes.coasts').'/{slug}', function (Request $request, $prefix, $city, $slug) {
        $data = array(
            'leaflet'=>    'true',
            'css'    =>    array(
                'assets/css/jquery.fancybox.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/jquery.fancybox.min.js',
                'assets/js/owl.carousel.min.js',
                'assets/js/front_office/coasts/coast.js',
            )
        );
        return view('front_office.coasts.coast', compact('data'));
    });

    Route::get('/{city}/'.__('routes.festivals'), function (Request $request, $prefix, $city) {
        $data = array(
            'css'    =>    array(
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/front_office/cities/index.js',
                'assets/js/front_office/festivals/festivals.js',
            ),
        );
        return view('front_office.festivals.festivals', compact('data'));
    });

    Route::get('/{city}/'.__('routes.festivals').'/{slug}', function (Request $request, $prefix, $city, $slug) {
        $data = array(
            'leaflet'=>    'true',
            'css'    =>    array(
                'assets/css/jquery.fancybox.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/owl.carousel.min.js',
                'assets/js/jquery.fancybox.min.js',
                'assets/js/front_office/festivals/festival.js',
            )
        );
        return view('front_office.festivals.festival', compact('data'));
    });

    Route::get('/{city}/'.__('routes.museums'), function (Request $request, $prefix, $city) {
        $data = array(
            'css'    =>    array(
                'assets/css/owl.theme.default.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/front_office/cities/index.js',
                'assets/js/front_office/museums/museums.js'
            ),
        );
        return view('front_office.museums.museums', compact('data'));
    });

    Route::get('/{city}/'.__('routes.museums').'/{slug}', function (Request $request, $prefix, $city, $slug) {
        $data = array(
            'leaflet'=>    'true',
            'css'    =>    array(
                'assets/css/jquery.fancybox.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/owl.carousel.min.js',
                'assets/js/jquery.fancybox.min.js',
                'assets/js/front_office/museums/museum.js',
            )
        );
        return view('front_office.museums.museum', compact('data'));
    });

    Route::get('/{city}/'.__('routes.night_spots'), function (Request $request, $prefix, $city) {
        $data = array(
            'css'    =>    array(
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/front_office/cities/index.js',
                'assets/js/front_office/night_spots/night_spots.js',
            ),
        );
        return view('front_office.night_spots.night_spots', compact('data'));
    });

    Route::get('/{city}/'.__('routes.night_spots').'/{slug}', function (Request $request, $prefix, $city, $slug) {
        $data = array(
            'leaflet'=>    'true',
            'css'    =>    array(
                'assets/css/jquery.fancybox.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/owl.carousel.min.js',
                'assets/js/jquery.fancybox.min.js',
                'assets/js/front_office/night_spots/night_spot.js',
            )
        );
        return view('front_office.night_spots.night_spot', compact('data'));
    });

    Route::get('/{city}/'.__('routes.points_of_interest'), function (Request $request, $prefix, $city) {
        $data = array(
            'css'    =>    array(
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/front_office/cities/index.js',
                'assets/js/front_office/points_of_interest/points_of_interest.js',
            ),
        );
        return view('front_office.points_of_interest.points_of_interest', compact('data'));
    });

    Route::get('/{city}/'.__('routes.points_of_interest').'/{slug}', function (Request $request, $prefix, $city, $slug) {
        $data = array(
            'leaflet'=>    'true',
            'css'    =>    array(
                'assets/css/jquery.fancybox.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/owl.carousel.min.js',
                'assets/js/jquery.fancybox.min.js',
                'assets/js/front_office/points_of_interest/point_of_interest.js',
            )
        );
        return view('front_office.points_of_interest.point_of_interest', compact('data'));
    });

    Route::get('/{city}/'.__('routes.street_markets'), function (Request $request, $prefix, $city) {
        $data = array(
            'css'    =>    array(
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/front_office/cities/index.js',
                'assets/js/front_office/street_markets/street_markets.js',
            ),
        );
        return view('front_office.street_markets.street_markets', compact('data'));
    });

    Route::get('/{city}/'.__('routes.street_markets').'/{slug}', function (Request $request, $prefix, $city, $slug) {
        $data = array(
            'leaflet'=>    'true',
            'css'    =>    array(
                'assets/css/jquery.fancybox.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/owl.carousel.min.js',
                'assets/js/jquery.fancybox.min.js',
                'assets/js/front_office/street_markets/street_market.js',
            )
        );
        return view('front_office.street_markets.street_market', compact('data'));
    });

    Route::get('/{city}/'.__('routes.routes'), function (Request $request, $prefix, $city) {
        $data = array(
            'css'    =>    array(
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/front_office/cities/index.js',
                'assets/js/front_office/routes/routes.js',
            ),
        );
        return view('front_office.routes.routes', compact('data'));
    });

    Route::get('/{city}/'.__('routes.routes').'/{slug}', function (Request $request, $prefix, $city, $slug) {
        $data = array(
            'leaflet'=>    'true',
            'css'    =>    array(
                'assets/css/jquery.fancybox.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/back_office/authorization.js',
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/owl.carousel.min.js',
                'assets/js/jquery.fancybox.min.js',
                'assets/js/front_office/routes/route.js',
            )
        );
        return view('front_office.routes.route', compact('data'));
    });

    Route::get('/{city}/', function (Request $request, $prefix, $city) {
        $data = array(
            'leaflet'=>    'true',
            'css'    =>    array(
                'assets/css/jquery.fancybox.min.css',
                'assets/css/back_office/spinner.css',
                'assets/css/owl.carousel.min.css',
                'assets/css/owl.theme.default.min.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/translations.js',
                'assets/js/front_office/index.js',
                'assets/js/owl.carousel.min.js',
                'assets/js/jquery.fancybox.min.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/smooth-scrollbar.js',
                'assets/js/front_office/cities/index.js',
                'assets/js/front_office/cities/city.js'
            )
        );
        return view('front_office.cities.city', compact('data'));
    });

    Route::get('/{city}/'.__('routes.guided_visits'), function (Request $request, $prefix, $city) {
        $data = array(
            'leaflet'=>    'true',
            'css'    =>    array(
                'assets/css/jquery.fancybox.min.css',
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(
                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/owl.carousel.min.js',
                'assets/js/front_office/routes/route.js',
                'assets/js/jquery.fancybox.min.js',
            )
        );
        return view('front_office.guided_visits.guide_visit', compact('data'));
    });
    
    Route::get('/{city}/'.__('routes.restaurant'), function (Request $request, $prefix, $city) {
        $data = array(
            'leaflet'=>    'true',
            'css'    =>    array(
                'assets/css/back_office/spinner.css',
            ),
            'js'    =>    array(

                'assets/js/front_office/index.js',
                'assets/js/index.js',
                'assets/js/helpers.js',
                'assets/js/owl.carousel.min.js',
                // 'assets/js/front_office/routes/route.js',
                // 'assets/js/jquery.fancybox.min.js',
            )
        );
        return view('front_office.restaurants.restaurant', compact('data'));
    });
});

// Route::get('/legal-warning', function () {
//     $data = array(
//         'css'    =>    array(
//             'assets/css/owl.carousel.min.css',
//             'assets/css/owl.theme.default.min.css',
//         ),
//         'js'    =>    array(
//             'assets/js/front_office/index.js',
//             'assets/js/owl.carousel.min.js',
//         ),
//     );
//     return view('front_office.sections.legal_warning', compact('data'));
// });

// Route::get('/privacy-polity', function () {
//     $data = array(
//         'css'    =>    array(
//             'assets/css/owl.carousel.min.css',
//             'assets/css/owl.theme.default.min.css',
//         ),
//         'js'    =>    array(
//             'assets/js/front_office/index.js',
//             'assets/js/owl.carousel.min.js',
//         ),
//     );
//     return view('front_office.sections.privacy_polity', compact('data'));
// });

// Route::get('/cookie-policy', function () {
//     $data = array(
//         'css'    =>    array(
//             'assets/css/owl.carousel.min.css',
//             'assets/css/owl.theme.default.min.css',
//         ),
//         'js'    =>    array(
//             'assets/js/front_office/index.js',
//             'assets/js/owl.carousel.min.js',
//         ),
//     );
//     return view('front_office.sections.cookie_policy', compact('data'));
// });

// Route::get('/coasts', function () {
//     $data = array(
//         'css'    =>    array(
//             'assets/css/owl.carousel.min.css',
//             'assets/css/owl.theme.default.min.css',
//         ),
//         'js'    =>    array(
//             'assets/js/front_office/index.js',
//             'assets/js/owl.carousel.min.js',
//         ),
//     );
//     return view('front_office.coasts.coasts', compact('data'));
// });

// Route::get('/coast', function () {
//     $data = array(
//         'leaflet'           =>    'true',
//         'css'    =>    array(
//             'assets/css/owl.carousel.min.css',
//             'assets/css/owl.theme.default.min.css',
//         ),
//         'js'    =>    array(
//             'assets/js/front_office/index.js',
//             'assets/js/owl.carousel.min.js',
//         ),
//     );
//     return view('front_office.coasts.coast', compact('data'));
// });