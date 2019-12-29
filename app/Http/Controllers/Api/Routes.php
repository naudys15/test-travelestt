<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\City;
use Travelestt\Models\Language;
use Travelestt\Models\Language_field;
use Travelestt\Models\Characteristic_entity;
use Travelestt\Models\Characteristic_category;
use Travelestt\Models\Route;
use Travelestt\Models\Route_translation;
use Travelestt\Models\Route_characteristic;
use Travelestt\Models\Route_valoration;
use Travelestt\Models\Station;
use Travelestt\Models\Station_translation;
use Travelestt\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManagerStatic as Image;

class Routes extends Controller
{
    public function __construct(Route $routes, Route_valoration $valorations, APILogin $api_login)
    {
        $this->routes = $routes;
        $this->valorations = $valorations;
        $this->folder = 'assets/images/routes/';
        $this->check_user = $api_login;
        $this->errors = [];
    }

    protected function validatorRoute(array $data, $id = null)
    {
        $messages = [
            'slug.required' => __('messages.slug_required'),
            'slug.string' => __('messages.slug_string'),
            'slug.unique' => __('messages.slug_unique'),
            // 'type.required' => __('messages.type_route_required'),
            // 'type.integer' => __('messages.type_route_integer'),
            // 'type.between' => __('messages.type_route_between'),
            // 'level.required' => __('messages.level_route_required'),
            // 'level.integer' => __('messages.level_route_integer'),
            // 'level.between' => __('messages.level_route_between'),
            'latitude_start.required' => __('messages.latitude_start_required'),
            'longitude_start.required' => __('messages.longitude_start_required'),
            'latitude_end.required' => __('messages.latitude_end_required'),
            'longitude_end.required' => __('messages.longitude_end_required'),
            // 'duration_value.required' => __('messages.duration_value_required'),
            // 'duration_value.integer' => __('messages.duration_value_integer'),
            // 'id.required' => __('messages.id_required'),
            // 'id.exists' => __('messages.id_exists'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
            // 'status.required' => __('messages.status_required'),
            // 'status.boolean' => __('messages.status_boolean')
        ];
    	$rules = [
            'slug' => 'required|string|unique:route,slug,{$id},id,deleted,NULL',
            // 'type' => 'required|integer|between:1,3',
            // 'level' => 'required|integer|between:1,3',
            'latitude' => 'required',
            'longitude' => 'required',
            // 'duration_value' => 'required|integer',
            // 'id' => 'required|exists:unitduration,id',
            'id' => 'required|exists:city,id',
            // 'status' => 'required|boolean'
        ];

        if ($id) {
            $rules['slug'] = Rule::unique('route', 'slug')->ignore($id, 'id');
        }

    	return Validator::make($data, $rules, $messages);
    }

    protected function validatorCharacteristic(array $data, $id = null)
    {
        $messages = [
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists')
        ];
    	$rules = [
            'id' => 'required|exists:route,id',
            'id' => 'required|exists:characteristicentity,id',
        ];

    	return Validator::make($data, $rules, $messages);
    }

    protected function validatorTranslation(array $data)
    {
        $messages = [
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
            'content.required' => __('messages.content_required'),
            'content.string' => __('messages.content_string')
        ];
    	$rules = [
            'id' => 'required|exists:route,id',
            'id' => 'required|exists:language,id',
            'id' => 'required|exists:languagefield,id',
            'content' => 'required|string'
        ];

    	return Validator::make($data, $rules, $messages);
    }

    protected function validatorLanguageFields(array $data, $language)
    {
        $messages = [
            'address_'.$language.'.required' => __('messages.address_required'),
            'address_'.$language.'.string' => __('messages.address_string'),
            'name_'.$language.'.required' => __('messages.name_required'),
            'name_'.$language.'.string' => __('messages.name_string'),
            'short_description_'.$language.'.required' => __('messages.short_description_required'),
            'short_description_'.$language.'.string' => __('messages.short_description_string'),
            'long_description_'.$language.'.required' => __('messages.long_description_required'),
            'long_description_'.$language.'.string' => __('messages.long_description_string'),
            'meta_title_'.$language.'.required' => __('messages.meta_title_required'),
            'meta_title_'.$language.'.string' => __('messages.meta_title_string'),
            'meta_description_'.$language.'.required' => __('messages.meta_description_required'),
            'meta_description_'.$language.'.string' => __('messages.meta_description_string'),
        ];
        $rules = [
            'address_'.$language => 'required|string',
            'name_'.$language => 'required|string',
            'short_description_'.$language => 'required|string',
            'long_description_'.$language => 'required|string',
            'meta_title_'.$language => 'required|string',
            'meta_description_'.$language => 'required|string',
            'station_name_'.$language => 'nullable|string',
            'station_description_'.$language => 'nullable|string',
        ];

    	return Validator::make($data, $rules, $messages);
    }

    protected function validatorComment(array $data)
    {
        $messages = [
            'id.required'         =>    __('messages.id_required'),
            'id.exists'           =>    __('messages.id_exists'),
            'title.required'      =>    __('messages.title_comment_required'),
            'title.max'           =>    __('messages.title_comment_max'),
            'content.required'    =>    __('messages.comment_required'),
            'rating.required'     =>    __('messages.rating_required'),
            'rating.integer'      =>    __('messages.rating_integer'),
            'rating.between'      =>    __('messages.rating_between'),
            // 'recaptcha.required'       =>    __('messages.recaptcha_required'),
            // 'recaptcha.captcha'        =>    __('messages.recaptcha_invalid'),
        ];
    	$rules = [
            'id'         =>    'required|exists:route,id',
            'title'      =>    'required|max:100',
            'content'    =>    'required',
            'rating'     =>    'required|integer|between:1,5',
            // 'recaptcha'       =>    'required|captcha',
        ];
    	return Validator::make($data, $rules, $messages);
    }

    protected function parseErrors($validated_errors, $type, $language = null)
    {
        $errors = [];
        if ($type == 'route') {
            $errors = [];
            foreach ($validated_errors->all() as $error) {
                foreach ($validated_errors->get('slug') as $message) {
                    $errors['slug'] = $message;
                }
                foreach ($validated_errors->get('type') as $message) {
                    $errors['type'] = $message;
                }
                foreach ($validated_errors->get('level') as $message) {
                    $errors['level'] = $message;
                }
                foreach ($validated_errors->get('latitude_start') as $message) {
                    $errors['latitude_start'] = $message;
                }
                foreach ($validated_errors->get('longitude_start') as $message) {
                    $errors['longitude_start'] = $message;
                }
                foreach ($validated_errors->get('latitude_end') as $message) {
                    $errors['latitude_end'] = $message;
                }
                foreach ($validated_errors->get('longitude_end') as $message) {
                    $errors['longitude_end'] = $message;
                }
                foreach ($validated_errors->get('duration_value') as $message) {
                    $errors['duration_value'] = $message;
                }
                foreach ($validated_errors->get('id') as $message) {
                    $errors['unit'] = $message;
                }
                foreach ($validated_errors->get('id') as $message) {
                    $errors['city'] = $message;
                }
                foreach ($validated_errors->get('status') as $message) {
                    $errors['status'] = $message;
                }
            }
        } else if ($type == 'translation') {
            foreach ($validated_errors->all() as $error) {
                foreach ($validated_errors->get('address_'.$language) as $message) {
                    $errors['address'] = $message;
                }
                foreach ($validated_errors->get('name_'.$language) as $message) {
                    $errors['name'] = $message;
                }
                foreach ($validated_errors->get('short_description_'.$language) as $message) {
                    $errors['short_description'] = $message;
                }
                foreach ($validated_errors->get('long_description_'.$language) as $message) {
                    $errors['long_description'] = $message;
                }
                foreach ($validated_errors->get('meta_title_'.$language) as $message) {
                    $errors['meta_title'] = $message;
                }
                foreach ($validated_errors->get('meta_description_'.$language) as $message) {
                    $errors['meta_description'] = $message;
                }
            }
        } else if ($type = 'comment') {
            foreach ($validated_errors->all() as $error) {
                foreach ($validated_errors->get('id') as $message) {
                    $errors['route'] = $message;
                }
                foreach ($validated_errors->get('content') as $message) {
                    $errors['content'] = $message;
                }
                foreach ($validated_errors->get('rating') as $message) {
                    $errors['rating'] = $message;
                }
                foreach ($validated_errors->get('title') as $message) {
                    $errors['title'] = $message;
                }
                foreach ($validated_errors->get('recaptcha') as $message) {
                    $errors['recaptcha'] = $message;
                }
            }
        }
        return $errors;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routes = $this->routes->formatRoute()->get();
        $routes = $this->routes->parseRouteToFront($routes);
        if (count($routes) == 0) {
            return response()->json(['message' => __('messages.routes_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $routes, 'status' => 'success'], 200);     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        
        $checkRole = $user->verifyRoleAuthorization($user->id);;;
        
        $checkPermission = $user->verifyPermissionAuthorization('route_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $latitudes = $request->latitudes;
            $longitudes = $request->longitudes;
            $elevations = $request->elevations;
            $route_has_stations = false;
            $languages_present_in_routes = [];

            if (count($latitudes) == count($longitudes) && count($latitudes) == count($elevations) && (count($latitudes) < 2 || count($longitudes) < 2)) {
                return response()->json(['message' => __('messages.no_coordinates_route'), 'status' => 'error'], 400);
            }
            $errors = [];
            foreach ($latitudes as $latitude) {
                if (!checkLatitude($latitude)) {
                    $errors['latitudes'] = __('messages.latitude_error_route');
                    break;
                }
            }
            foreach ($longitudes as $longitude) {
                if (!checkLongitude($longitude)) {
                    $errors['longitudes'] = __('messages.longitude_error_route');
                    break;
                }
            }
            if ($errors != []) {
                return response()->json(['message' => $errors, 'status' => 'error'], 400);
            }

            if (count($latitudes) > 2 || count($longitudes) > 2 || count($elevations) > 2) {
                $route_has_stations = true;
                $expected_stations = count($latitudes) - 2;
            }

            $latitude_start = array_shift($latitudes);
            $latitude_end = array_pop($latitudes);
            $longitude_start = array_shift($longitudes);
            $longitude_end = array_pop($longitudes);

            $data_route = [
                'slug' => $request->slug,
                // 'type' => $request->type,
                // 'level' => $request->level,
                'latitude_start' => $latitude_start,
                'longitude_start' => $longitude_start,
                'latitude_end' => $latitude_end,
                'longitude_end' => $longitude_end,
                // 'duration_value' => $request->duration_value,
                // 'id' => $request->unit,
                'id' => $request->city,
                // 'status' => $request->status
            ];
		    $validated = $this->validatorRoute($data_route);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'route');
                $this->errors = $errors;
            }

            if (!isset($request->translations)) {
                return response()->json(['message' => __('messages.no_translations_error'), 'status' => 'error'], 400);
            } else {
                foreach ($request->translations as $key => $translation) {
                    $dataTranslation = [];
                    $language = Language::where('name' ,'=', $key)->first();
                    if ($language == null) {
                        return response()->json(['message' => __('messages.no_language_error'), 'status' => 'error'], 400);
                    }
                    foreach ($translation as $key_translation => $translate) {
                        $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                        if ($language_field == null) {
                            return response()->json(['message' => __('messages.no_language_field_error'), 'status' => 'error'], 400);
                        }
                        $dataTranslation[$key_translation.'_'.strtolower($language->name)] = $translate;
                    }
                    $validated = $this->validatorLanguageFields($dataTranslation, $language->name);
                    if ($validated->fails()) {
                        $errors = $this->parseErrors($validated->errors(), 'translation', $language->name);
                        $this->errors['translations'][$language->name] = $errors;
                    } else {
                        $languages_present_in_routes[] = $language->name;
                    }
                }
            }

            if (isset($request->stations) && $route_has_stations) {
                $languages_present_in_stations = [];
                $stations_name = [];
                foreach ($request->stations as $key => $station) {
                    $in_routes = false;
                    foreach ($languages_present_in_routes as $language) {
                        if ($language == $key) {
                            $in_routes = true;
                        }
                    }
                    if ($in_routes) {
                        $stations_quantity = 0;
                        $stations[$key] = [];
                        foreach ($station as $name) {
                            $stations_name[$stations_quantity][$key] = $name;
                            $stations_quantity++;
                        }
                        $languages_present_in_stations[] = $key;
                        if ($stations_quantity < $expected_stations) {
                            $this->errors['stations'][$key] = __('messages.missing_station_name_error');
                        }
                    }
                }
                $result_languages_stations = array_diff($languages_present_in_routes, $languages_present_in_stations);
                foreach ($result_languages_stations as $results_language) {
                    $this->errors['stations'][$results_language] = __('messages.missing_stations_name_error');
                }
            }
            if ($this->errors != []){
                return response()->json(['message' => $this->errors, 'status' => 'error'], 400);
            }

            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                    if ($char_enti == null) {
                        return response()->json(['message' => __('messages.no_characteristic_error'), 'status' => 'error'], 400);
                    } elseif ($char_enti != null) {
                        $char_cat = Characteristic_category::where('id', '=', $char_enti->id)
                                                        ->where('id', '=', 7)->first();
                        if ($char_cat == null) {
                            return response()->json(['message' => __('messages.no_category_error'), 'status' => 'error'], 400);
                        }
                    }
                }
            }
            
            $input_route = $data_route;
            $input_route['slopes'] = serialize(calculateSlopes($latitudes, $longitudes, $elevations));
            $input_route['addedby'] = $user->id;
            $input_route['updateby'] = $user->id;
            $city = City::where('id', '=', $request->city)->first();
            $route = $this->routes->create($input_route);
            $route->city()->associate($city);
            $name_images = [];

            if (isset($request->file)) {
                if (count($request->file) > 5) {
                    return response()->json(['message' => __('messages.image_maximum_error'), 'status' => 'error'], 400);
                }
                $route_images = 0;
                foreach ($request->file as $file) {
                    $name = $file->getClientOriginalName();
                    $file_name = 'route-'.$route->id.'-'.$route_images.'.'.$file->getClientOriginalExtension();
                    $url = $this->folder.$file_name;
                    if (Storage::disk('local')->exists($url)) {
                        Storage::delete($url);
                    }
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(800, 600);
                    $image_resize->save($url);
                    $name_images[] = $file_name;
                    $route_images++;
                } 
            } else {
                $route->delete();
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $media = serialize($name_images);
            $route->media = $media;
            $route->save();

            if (isset($stations_name) && isset($expected_stations) && $expected_stations > 0) {
                $latitudes_stations = $latitudes;
                $longitudes_stations = $longitudes;

                for ($i = 0; $i < count($latitudes_stations); $i++) {
                    $data = [
                        'latitude' => $latitudes_stations[$i],
                        'longitude' => $longitudes_stations[$i],
                        'id' => $route->id,
                        'created' => date('Y-m-d'),
                        'updated' => date('Y-m-d'),
                        'id' => $route->id
                    ];
                    $station = Station::create($data);
                    $station->city()->associate($city);
                    $station->route()->associate($route);
                    $route->save();

                    foreach ($stations_name[$i] as $key_translation_station => $name_station) {
                        $language = Language::where('name', '=', $key_translation_station)->first();
                        $language_field = Language_field::where('name' ,'=', 'name')->first();
                        $data = [
                            'id' => $station->id,
                            'id' => $language->id,
                            'id' => $language_field->id,
                            'content' => $name_station
                        ];
                        $translation_station = Station_translation::create($data);
                        $translation_station->station()->associate($station);
                        $station->save();
                    }
                }
            }

            foreach ($request->translations as $key => $translation) {
                $dataTranslation = [];
                $language = Language::where('name' ,'=', $key)->first();
                foreach ($translation as $key_translation => $translate) {
                    $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                    $data = [
                        'id' => $route->id,
                        'id' => $language->id,
                        'id' => $language_field->id,
                        'content' => $translate
                    ];
                    $translation_route = Route_translation::create($data);
                    $translation_route->route()->associate($route);
                    $route->save();
                }
            }
            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                    $data = [
                        'id' => $route->id,
                        'id' => $char_enti->id
                    ];
                    $characteristic_route = Route_characteristic::create($data);
                    $characteristic_route->route()->associate($route);
                    $route->save();
                }
            }
            storeLogActivity('route_create', $user->id);
        	return response()->json(['message' => __('messages.route_create_success'), 'status' => 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.route_create_error'), 'status' => 'error'], 400);
    	}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $routes = $this->routes->formatRoute()->where('id', '=', $id)->get();
        $routes = $this->routes->parseRouteToFront($routes);
        if (count($routes) == 0) {
            return response()->json(['message' => __('messages.route_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $routes, 'status' => 'success'], 200);     
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        
        $checkRole = $user->verifyRoleAuthorization($user->id);

        $checkPermission = $user->verifyPermissionAuthorization('route_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $route = Route::where('id', '=', $id)->first();
                if ($route != null) {
                    if ($route->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $route = Route::find($id);
            }

            $route = Route::find($id);
            if ($route == null) {
                return response()->json(['message' => __('messages.route_not_found'), 'status' => 'error'], 404);
            }
            $pictures_already_route = unserialize($route->media);
            if (count($pictures_already_route) == 0 && !$request->hasFile('file')) {
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $latitudes = $request->latitudes;
            $longitudes = $request->longitudes;
            $elevations = $request->elevations;
            $route_has_stations = false;
            $languages_present_in_routes = [];

            if (count($latitudes) == count($longitudes) && count($latitudes) == count($elevations) && (count($latitudes) < 2 || count($longitudes) < 2)) {
                return response()->json(['message' => __('messages.no_coordinates_route'), 'status' => 'error'], 400);
            }
            $errors = [];
            foreach ($latitudes as $latitude) {
                if (!checkLatitude($latitude)) {
                    $errors['latitudes'] = __('messages.latitude_error_route');
                    break;
                }
            }
            foreach ($longitudes as $longitude) {
                if (!checkLongitude($longitude)) {
                    $errors['longitudes'] = __('messages.longitude_error_route');
                    break;
                }
            }
            if ($errors != []) {
                return response()->json(['message' => $errors, 'status' => 'error'], 400);
            }

            if (count($latitudes) > 2 || count($longitudes) > 2 || count($elevations) > 2) {
                $route_has_stations = true;
                $expected_stations = count($latitudes) - 2;
            }

            $latitude_start = array_shift($latitudes);
            $latitude_end = array_pop($latitudes);
            $longitude_start = array_shift($longitudes);
            $longitude_end = array_pop($longitudes);

            $data_route = [
                'slug' => $request->slug,
                // 'type' => $request->type,
                // 'level' => $request->level,
                'latitude_start' => $latitude_start,
                'longitude_start' => $longitude_start,
                'latitude_end' => $latitude_end,
                'longitude_end' => $longitude_end,
                // 'duration_value' => $request->duration_value,
                // 'id' => $request->unit,
                'id' => $request->city,
                // 'status' => $request->status
            ];
		    $validated = $this->validatorRoute($data_route, $id);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'route');
                $this->errors = $errors;
            }

            if (!isset($request->translations)) {
                return response()->json(['message' => __('messages.no_translations_error'), 'status' => 'error'], 400);
            } else {
                foreach ($request->translations as $key => $translation) {
                    $dataTranslation = [];
                    $language = Language::where('name' ,'=', $key)->first();
                    if ($language == null) {
                        return response()->json(['message' => __('messages.no_language_error'), 'status' => 'error'], 400);
                    }
                    foreach ($translation as $key_translation => $translate) {
                        $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                        if ($language_field == null) {
                            return response()->json(['message' => __('messages.no_language_field_error'), 'status' => 'error'], 400);
                        }
                        $dataTranslation[$key_translation.'_'.strtolower($language->name)] = $translate;
                    }
                    $validated = $this->validatorLanguageFields($dataTranslation, $language->name);
                    if ($validated->fails()) {
                        $errors = $this->parseErrors($validated->errors(), 'translation', $language->name);
                        $this->errors['translations'][$language->name] = $errors;
                    } else {
                        $languages_present_in_routes[] = $language->name;
                    }
                }
            }

            if (isset($request->stations) && $route_has_stations) {
                $languages_present_in_stations = [];
                $stations_name = [];
                foreach ($request->stations as $key => $station) {
                    $in_routes = false;
                    foreach ($languages_present_in_routes as $language) {
                        if ($language == $key) {
                            $in_routes = true;
                        }
                    }
                    if ($in_routes) {
                        $stations_quantity = 0;
                        $stations[$key] = [];
                        foreach ($station as $name) {
                            $stations_name[$stations_quantity][$key] = $name;
                            $stations_quantity++;
                        }
                        $languages_present_in_stations[] = $key;
                        if ($stations_quantity < $expected_stations) {
                            $this->errors['stations'][$key] = __('messages.missing_station_name_error');
                        }
                    }
                }
            }
            $result_languages_stations = array_diff($languages_present_in_routes, $languages_present_in_stations);
            foreach ($result_languages_stations as $results_language) {
                $this->errors['stations'][$results_language] = __('messages.missing_stations_name_error');
            }
            if ($this->errors != []){
                return response()->json(['message' => $this->errors, 'status' => 'error'], 400);
            }

            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                    if ($char_enti == null) {
                        return response()->json(['message' => __('messages.no_characteristic_error'), 'status' => 'error'], 400);
                    } elseif ($char_enti != null) {
                        $char_cat = Characteristic_category::where('id', '=', $char_enti->id)
                                                        ->where('id', '=', 7)->first();
                        if ($char_cat == null) {
                            return response()->json(['message' => __('messages.no_category_error'), 'status' => 'error'], 400);
                        }
                    }
                }
            }
            $input_route = $data_route;
            $input_route['slopes'] = serialize(calculateSlopes($latitudes, $longitudes, $elevations));
            $input_route['updateby'] = $user->id;
            $aux_route = $route;
            $city = City::where('id', '=', $request->city)->first();
            $route->fill($input_route);
            $route->city()->dissociate();
            $route->city()->associate($city);
            $route->save();

            $pictures_already_route = unserialize($route->media);
            $pictures_sent_in_request_already = $request->files_already;
            $pictures_to_delete = [];
            $pictures_to_maintain = [];

            if (count($pictures_sent_in_request_already) == 0) {
                $pictures_to_delete = $pictures_already_route;
            } else {
                foreach ($pictures_already_route as $picture_route) {
                    $band = false;
                    foreach ($pictures_sent_in_request_already as $picture_request) {
                        if ($picture_route == $picture_request) {
                            $band = true;
                        }
                    }
                    if (!$band) {
                        $pictures_to_delete[] = $picture_route;
                    } else {
                        $pictures_to_maintain[] = $picture_route;
                    }
                }
            }
            
            foreach ($pictures_to_delete as $picture_delete) {
                $url = $this->folder.$picture_delete;
                Storage::delete($url);
            }
            $route_images = 0;
            $name_images = [];
            foreach ($pictures_to_maintain as $picture_rename) {
                $info = new \SplFileInfo($picture_rename);
                $file_name = 'route-'.$route->id.'-'.$route_images.'.'.$info->getExtension();
                $url = $this->folder.$picture_rename;
                $new_url = $this->folder.$file_name;
                if ($url != $new_url) {
                    Storage::move($url, $new_url);
                }
                $name_images[] = $file_name;
                $route_images++;
            }

            if (isset($request->file)) {
                if ((count($request->file) + $route_images) > 5) {
                    $route = $aux_route;
                    $route->save();
                    return response()->json(['message' => __('messages.image_maximum_error'), 'status' => 'error'], 400);
                }
                foreach ($request->file as $file) {
                    $name = $file->getClientOriginalName();
                    $file_name = 'route-'.$route->id.'-'.$route_images.'.'.$file->getClientOriginalExtension();
                    $url = $this->folder.$file_name;
                    if (Storage::disk('local')->exists($url)) {
                        Storage::delete($url);
                    }
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(800, 600);
                    $image_resize->save($url);
                    $name_images[] = $file_name;
                    $route_images++;
                } 
            } elseif (!isset($request->file) && count($pictures_already_route) == 0) {
                $route = $aux_route;
                $route->save();
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $media = serialize($name_images);
            $route->media = $media;
            $route->save();

            $stations = Station::where('id', '=', $route->id)->get();
            foreach ($stations as $station) {
                $station->delete();
            }

            if (isset($stations_name) && isset($expected_stations) && $expected_stations > 0) {
                $latitudes_stations = $latitudes;
                $longitudes_stations = $longitudes;

                for ($i = 0; $i < count($latitudes_stations); $i++) {
                    $data = [
                        'latitude' => $latitudes_stations[$i],
                        'longitude' => $longitudes_stations[$i],
                        'id' => $route->id,
                        'created' => date('Y-m-d'),
                        'updated' => date('Y-m-d'),
                        'id' => $route->id
                    ];
                    $station = Station::create($data);
                    $station->city()->associate($city);
                    $station->route()->associate($route);
                    $route->save();

                    foreach ($stations_name[$i] as $key_translation_station => $name_station) {
                        $language = Language::where('name', '=', $key_translation_station)->first();
                        $language_field = Language_field::where('name' ,'=', 'name')->first();
                        $data = [
                            'id' => $station->id,
                            'id' => $language->id,
                            'id' => $language_field->id,
                            'content' => $name_station
                        ];
                        $translation_station = Station_translation::create($data);
                        $translation_station->station()->associate($station);
                        $station->save();
                    }
                }
            }

            $translations = Route_translation::where('id', '=', $route->id)->get();
            foreach ($translations as $translation) {
                $translation->delete();
            }

            foreach ($request->translations as $key => $translation) {
                $dataTranslation = [];
                $language = Language::where('name' ,'=', $key)->first();
                foreach ($translation as $key_translation => $translate) {
                    $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                    $data = [
                        'id' => $route->id,
                        'id' => $language->id,
                        'id' => $language_field->id,
                        'content' => $translate
                    ];
                    $translation_route = Route_translation::create($data);
                    $translation_route->route()->associate($route);
                    $route->save();
                }
            }

            $characteristics = Route_characteristic::where('id', '=', $route->id)->get();
            foreach ($characteristics as $characteristic) {
                $characteristic->delete();
            }
            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                    $data = [
                        'id' => $route->id,
                        'id' => $char_enti->id
                    ];
                    $characteristic_route = Route_characteristic::create($data);
                    $characteristic_route->route()->associate($route);
                    $route->save();
                }
            }
            storeLogActivity('route_update', $user->id);
        	return response()->json(['message' => __('messages.route_update_success'), 'status'=> 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.route_update_error'), 'status' => 'error'], 400);
    	}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        
        $checkRole = $user->verifyRoleAuthorization($user->id);

        $checkPermission = $user->verifyPermissionAuthorization('route_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $route = Route::where('id', '=', $id)->first();
                if ($route != null) {
                    if ($route->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $route = $this->routes->find($id);
            }

            if ($route == null) {
	    		return response()->json(['message' => __('messages.route_not_found'), 'status' => 'error'], 404);
            }

            $images = unserialize($route->media);
            foreach ($images as $image) {
                $url = $this->folder.$image;
                Storage::delete($url);
            }
            $route->delete();
            storeLogActivity('route_delete', $user->id);
            return response()->json(['message' => __('messages.route_delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.route_delete_error'), 'status' => 'error']);
        }      
    }

    /**
     * Change the status of the route
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        
        $checkRole = $user->verifyRoleAuthorization($user->id);

        $checkPermission = $user->verifyPermissionAuthorization('route_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $route = Route::where('id', '=', $id)->first();
                if ($route != null) {
                    if ($route->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $route = $this->routes->find($id);
            }

            if ($route == NULL) {
	    		return response()->json(['message' => __('messages.route_not_found'), 'status' => 'error'], 404);
            }

            if ($route->status == 0) {
                $route->status = 1;
            } elseif ($route->status == 1) {
                $route->status = 0;
            }
            $route->save();
            storeLogActivity('route_update_status', $user->id);
            return response()->json(['message' => __('messages.route_update_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.route_update_error'), 'status' => 'error']);
        }      
    }

    /**
     * Change the outstanding of the route
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeOutstanding(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        
        $checkRole = $user->verifyRoleAuthorization($user->id);

        $checkPermission = $user->verifyPermissionAuthorization('route_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $route = Route::where('id', '=', $id)->first();
                if ($route != null) {
                    if ($route->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $route = $this->routes->find($id);
            }

            if ($route == NULL) {
	    		return response()->json(['message' => __('messages.route_not_found'), 'status' => 'error'], 404);
            }

            if ($route->outstanding == 0) {
                $route->outstanding = 1;
            } elseif ($route->outstanding == 1) {
                $route->outstanding = 0;
            }
            $route->save();
            storeLogActivity('route_update_outstanding', $user->id);
            return response()->json(['message' => __('messages.route_update_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.route_update_error'), 'status' => 'error']);
        }      
    }


    public function getRoutesBySlug(Request $request, $slug)
    {
        $routes = $this->routes->formatRoute()->bySlug($slug)->get();
        $routes = $this->routes->parseRouteToFront($routes);
        if (count($routes) == 0) {
            return response()->json(['message' => __('messages.routes_not_found'),'status' => 'error'], 404); 
        }

        return response()->json(['message' => $routes, 'status' => 'success'], 200); 
    }

    public function getRoutesByCity(Request $request, $city)
    {
        $routes = $this->routes->formatRoute()->byCity($city)->get();
        $routes = $this->routes->parseRouteToFront($routes);
        if (count($routes) == 0) {
            return response()->json(['message' => __('messages.routes_not_found'),'status' => 'error'], 404); 
        }

        return response()->json(['message' => $routes, 'status' => 'success'], 200); 
    }

    public function searchRoutes(Request $request)
    {
        $filters = null;
        if (isset($request->city)) {
            $city = explode(',', $request->city);
            $city = array_shift($city);
            $getCityBySlug = City::bySlug($city)->first();
            if (!$getCityBySlug) {
                return response()->json(['message' => __('messages.routes_not_found'), 'status' => 'error'], 404);
            }
        } else {
            return response()->json(['message' => __('messages.routes_not_found'), 'status' => 'error'], 404);
        }
        if (isset($request->types)) {
            $types = explode(',', $request->types);
        } else {
            $types = null;
        }
        if (isset($request->levels)) {
            $levels = explode(',', $request->level);
        } else {
            $levels = null;
        }
        if ($types != null && $levels != null) {
            $filters = array_merge($types, $levels);
        } else if ($types != null && $levels == null) {
            $filters = $types;
        } else if ($types == null && $levels != null) {
            $filters = $levels;
        }
        $routes = $this->routes->byCharacteristics($filters)->byCity($getCityBySlug->slug)->formatRoute()->distinct()->get();
        $all_routes = $this->routes->byCity($city)->formatRoute()->inRandomOrder()->get();
        $new_routes = [];
        foreach ($routes as $route) {
            $new_routes[] = $route;
        }
        foreach ($all_routes as $all_route) {
            $band = false;
            foreach ($new_routes as $new_route) {
                if ($all_route['id'] == $new_route['id']) {
                    $band = true;
                }
            }
            if (!$band) {
                $new_routes[] = $all_route;
            }
        }
        if ($request->language) {
            $new_routes = $this->routes->parseRouteToFront($new_routes, $request->language);
        } else {
            $new_routes = $this->routes->parseRouteToFront($new_routes);
        }
        if (count($new_routes) == 0) {
            return response()->json(['message' => __('messages.routes_not_found'), 'status' => 'error'], 404);
        }
        if (floatval($getCityBySlug->latitude) && floatval($getCityBySlug->longitude)) {
            for ($i = 0; $i < count($new_routes); $i++) {
                $new_routes[$i]['distance'] = calculateDistance($new_routes[$i]['location']['latitude'], $new_routes[$i]['location']['longitude'], $getCityBySlug->latitude, $getCityBySlug->longitude);
            }
        }
        if (isset($request->sort)) {
            if ($request->sort == 'name') {
                if ($request->order == 'desc') {
                    arraySortBy($new_routes, 'name', SORT_DESC);
                } else {
                    arraySortBy($new_routes, 'name');
                }
            } elseif ($request->sort == 'distance') {
                if ($request->order == 'desc') {
                    arraySortBy($new_routes, 'distance', SORT_DESC);
                } else {
                    arraySortBy($new_routes, 'distance');
                }
            } elseif ($request->sort == 'valoration') {
            }
        }
        return response()->json(['message' => $new_routes, 'status' => 'success'], 200);
    }
    
    public function fetchComments($id)
    {
        $route = $this->routes->where('id', '=', $id)->get();
        if ($route->isEmpty()) {
            return response()->json(['message' => __('messages.route_not_found'),'status' => 'error'], 404);
        }
        $comments = $this->valorations->comments($id);
        if ($comments->isEmpty()) {
            return response()->json(['message' => __('messages.no_comments_found'), 'status' => 'success'], 404);
        }
        return response()->json(['message' => $comments, 'status' => 'success'], 200);
    }

    public function storeComment(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        try {
            $data = [
                'id'         =>    $id,
                'id'         =>    $user->id,
                'title'      =>    $request->title,
                'content'    =>    $request->content,
                'rating'     =>    $request->rating,
                'recaptcha'       =>    $request->recaptcha
            ];
            $validated = $this->validatorComment($data);
	    	if ($validated->errors()) {
                $errors = $this->parseErrors($validated->errors(), 'comment');
                $this->errors = $errors;
            }
            if ($this->errors != []){
                return response()->json(['message' => $this->errors, 'status' => 'error'], 400);
            }
            $route = $this->routes->where('id', '=', $id)->first();
            $comments_with_trash = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->onlyTrashed()->first();
            $comments_without_trash = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->first();
            if ($comments_with_trash != NULL && $comments_without_trash == NULL) {
                $comments_with_trash->restore();
                $data['status'] = 0;
                $comments_with_trash->fill($data);
                $comments_with_trash->pointOfInterest()->dissociate();
                $comments_with_trash->user()->dissociate();
                $comments_with_trash->pointOfInterest()->associate($route);
                $comments_with_trash->user()->associate($user);
                $comments_with_trash->save();
                return response()->json(['message' => __('messages.comment_create_success'), 'status' => 'success'], 201);
            } else if ($comments_with_trash == NULL && $comments_without_trash != NULL)  {
                return response()->json(['message' => __('messages.comment_duplicate'), 'status' => 'error'], 400);
            } else {
                $comment = $this->valorations->create($data);
                $comment->fill($data);
                $comment->pointOfInterest()->associate($route);
                $comment->user()->associate($user);
                $comment->save();
                return response()->json(['message' => __('messages.comment_create_success'), 'status' => 'success'], 201);
            }
    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.comment_create_error'), 'status' => 'error'], 500);
    	}
    }

    public function updateComment(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        try {
            $data = [
                'id'         =>    $id,
                'id'         =>    $user->id,
                'title'      =>    $request->title,
                'content'    =>    $request->content,
                'rating'     =>    $request->rating,
            ];
            $validated = $this->validatorComment($data);
	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'comment');
                $this->errors = $errors;
            }
            if ($this->errors != []){
                return response()->json(['message' => $this->errors, 'status' => 'error'], 400);
            }
            $comment = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->first();
            if ($comment == NULL) {
                return response()->json(['message' => __('messages.comment_no_exists'), 'status' => 'error'], 400);
            }
            if ($comment->status > 0) {
                return response()->json(['message' => __('messages.comment_moderate'), 'status' => 'error'], 400);
            }
            $route = $this->routes->where('id', '=', $id)->first();
            $comment->fill($data);
            $comment->pointOfInterest()->dissociate();
            $comment->user()->dissociate();
            $comment->pointOfInterest()->associate($route);
            $comment->user()->associate($user);
            $comment->save();
            return response()->json(['message' => __('messages.comment_update_success'), 'status' => 'success'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => __('messages.comment_update_error'), 'status' => 'error'], 500);
        }
    }

    public function destroyComment(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        try {
            $comment = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id);
            if ($comment->count() == 0) {
                return response()->json(['message' => 'message.comment_no_exists', 'status' => 'error'], 400);
            }
            $comment->delete();
            return response()->json([], 204);
        } catch (Exception $e) {
            return response()->json(['message' => __('messages.comment_delete_error'), 'status' => 'error'], 500);
        }
    }
}
