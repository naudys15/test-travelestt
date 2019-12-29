<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\Language;
use Travelestt\Models\Language_field;
use Travelestt\Models\Characteristic_entity;
use Travelestt\Models\Characteristic_category;
use Travelestt\Models\City;
use Travelestt\Models\Night_spot;
use Travelestt\Models\Night_spot_translation;
use Travelestt\Models\Night_spot_characteristic;
use Travelestt\Models\Night_spot_valoration;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Night_spots extends Controller
{
    public function __construct(Night_spot $night_spots, Night_spot_valoration $valorations, APILogin $api_login)
    {
        $this->night_spots = $night_spots;
        $this->valorations = $valorations;
        $this->folder = 'assets/images/night_spots/';
        $this->check_user = $api_login;
        $this->errors = [];
    }

    protected function validatorNightSpot(array $data, $id = null)
    {
        $messages = [
            'slug.required' => __('messages.slug_required'),
            'slug.string' => __('messages.slug_string'),
            'slug.unique' => __('messages.slug_unique'),
            'latitude.required' => __('messages.latitude_required'),
            'longitude.required' => __('messages.longitude_required'),
            'phonenumber.required' =>  __('messages.phonenumber_required'),
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
            'slug' => 'required|string|unique:nightspot,slug,{$id},id,deleted,NULL',
            'latitude' => 'required',
            'longitude' => 'required',
            'phonenumber' => 'nullable',
            // 'duration_value' => 'required|integer',
            // 'id' => 'required|exists:unitduration,id',
            'id' => 'required|exists:city,id',
            // 'status' => 'required|boolean'
        ];

        if ($id) {
            $rules['slug'] = Rule::unique('nightspot', 'slug')->ignore($id, 'id');
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
            'id' => 'required|exists:nightspot,id',
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
            'id' => 'required|exists:nightspot,id',
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
            'id'         =>    'required|exists:nightspot,id',
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
        if ($type == 'night_spot') {
            $errors = [];
            foreach ($validated_errors->all() as $error) {
                foreach ($validated_errors->get('slug') as $message) {
                    $errors['slug'] = $message;
                }
                foreach ($validated_errors->get('latitude') as $message) {
                    $errors['latitude'] = $message;
                }
                foreach ($validated_errors->get('longitude') as $message) {
                    $errors['longitude'] = $message;
                }
                foreach ($validated_errors->get('phonenumber') as $message) {
                    $errors['phonenumber'] = $message;
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
                    $errors['spot'] = $message;
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
        $night_spots = $this->night_spots->formatNightSpot()->get();
        $night_spots = $this->night_spots->parseNightSpotToFront($night_spots);
        if (count($night_spots) == 0) {
            return response()->json(['message' => __('messages.night_spots_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $night_spots, 'status' => 'success'], 200);     
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
        
        $checkRole = $user->verifyRoleAuthorization($user->id);;
        
        $checkPermission = $user->verifyPermissionAuthorization('night_spot_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $data_night_spot = [
                'slug' => $request->slug,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'phonenumber' => $request->phonenumber,
                // 'duration_value' => $request->duration_value,
                // 'id' => $request->unit,
                'id' => $request->city,
                // 'status' => $request->status
            ];
		    $validated = $this->validatorNightSpot($data_night_spot);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'night_spot');
                $this->errors = $errors;
            }

            if (!isset($request->translations)) {
                return response()->json(['message' => _('messages.no_translations_error'), 'status' => 'error'], 400);
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
                            return response()->json(['message' =>  __('messages.no_language_field_error'), 'status' => 'error'], 400);
                        }
                        $dataTranslation[$key_translation.'_'.strtolower($language->name)] = $translate;
                    }
                    $validated = $this->validatorLanguageFields($dataTranslation, $language->name);
                    if ($validated->fails()) {
                        $errors = $this->parseErrors($validated->errors(), 'translation', $language->name);
                        $this->errors['translations'][$language->name] = $errors;
                    }
                }
                if ($this->errors != []){
                    return response()->json(['message' => $this->errors, 'status' => 'error'], 400);
                }
            }
            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('characteristicentity.name' ,'=', $characteristic)
                                                        ->join('characteristiccategory', 'characteristiccategory.id', '=', 'characteristicentity.id')
                                                        ->where('characteristiccategory.id', '=', 4)
                                                        ->first();
                    if ($char_enti == null) {
                        return response()->json(['message' => __('messages.no_characteristic_error'), 'status' => 'error'], 400);
                    } elseif ($char_enti != null) {
                        $char_cat = Characteristic_category::where('id', '=', $char_enti->id)
                                                        ->where('id', '=', 4)->first();
                        if ($char_cat == null) {
                            return response()->json(['message' => __('messages.no_category_error'), 'status' => 'error'], 400);
                        }
                    }
                }
            }
            $input_night_spot = $data_night_spot;
            $input_night_spot['addedby'] = $user->id;
            $input_night_spot['updateby'] = $user->id;
            $city = City::where('id', '=', $request->city)->first();
            $night_spot = $this->night_spots->create($input_night_spot);
            $night_spot->city()->associate($city);
            $name_images = [];

            if (isset($request->file)) {
                if (count($request->file) > 5) {
                    return response()->json(['message' => __('messages.image_maximum_error'), 'status' => 'error'], 400);
                }
                $night_spot_images = 0;
                foreach ($request->file as $file) {
                    $name = $file->getClientOriginalName();
                    $file_name = 'night_spot-'.$night_spot->id.'-'.$night_spot_images.'.'.$file->getClientOriginalExtension();
                    $url = $this->folder.$file_name;
                    if (Storage::disk('local')->exists($url)) {
                        Storage::delete($url);
                    }
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(800, 600);
                    $image_resize->save($url);
                    $name_images[] = $file_name;
                    $night_spot_images++;
                } 
            } else {
                $night_spot->delete();
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $media = serialize($name_images);
            $night_spot->media = $media;
            $night_spot->save();

            foreach ($request->translations as $key => $translation) {
                $dataTranslation = [];
                $language = Language::where('name' ,'=', $key)->first();
                foreach ($translation as $key_translation => $translate) {
                    $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                    $data = [
                        'id' => $night_spot->id,
                        'id' => $language->id,
                        'id' => $language_field->id,
                        'content' => $translate
                    ];
                    $translation_night_spot = Night_spot_translation::create($data);
                    $translation_night_spot->nightspot()->associate($night_spot);
                    $night_spot->save();
                }
            }
            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                    $data = [
                        'id' => $night_spot->id,
                        'id' => $char_enti->id
                    ];
                    $characteristic_night_spot = Night_spot_characteristic::create($data);
                    $characteristic_night_spot->nightspot()->associate($night_spot);
                    $night_spot->save();
                }
            }
            storeLogActivity('night_spot_create', $user->id);
        	return response()->json(['message' => __('messages.night_spot_create_success'), 'status' => 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.night_spot_create_error'), 'status' => 'error'], 400);
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
        $night_spot = $this->night_spots->formatNightSpot()->where('id', '=', $id)->get();
        $night_spot = $this->night_spots->parseNightSpotToFront($night_spot);
        if (count($night_spot) == 0) {
            return response()->json(['message' => __('messages.night_spot_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $night_spot, 'status' => 'success'], 200);     
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
        
        $checkRole = $user->verifyRoleAuthorization($user->id);;

        $checkPermission = $user->verifyPermissionAuthorization('night_spot_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $night_spot = Night_spot::where('id', '=', $id)->first();
                if ($night_spot != null) {
                    if ($night_spot->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $night_spot = Night_spot::find($id);
            }
            
            if ($night_spot == null) {
                return response()->json(['message' => __('messages.night_spot_not_found'), 'status' => 'error'], 404);
            }
            $pictures_already_night_spot = unserialize($night_spot->media);
            if (count($pictures_already_night_spot) == 0 && !$request->hasFile('file')) {
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $data_night_spot = [
                'slug' => $request->slug,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'phonenumber' => $request->phonenumber,
                // 'duration_value' => $request->duration_value,
                // 'id' => $request->unit,
                'id' => $request->city,
                // 'status' => $request->status
            ];
		    $validated = $this->validatorNightSpot($data_night_spot, $id);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'night_spot');
                $this->errors = $errors;
            }

            if (!isset($request->translations)) {
                return response()->json(['message' => _('messages.no_translations_error'), 'status' => 'error'], 400);
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
                            return response()->json(['message' =>  __('messages.no_language_field_error'), 'status' => 'error'], 400);
                        }
                        $dataTranslation[$key_translation.'_'.strtolower($language->name)] = $translate;
                    }
                    $validated = $this->validatorLanguageFields($dataTranslation, $language->name);
                    if ($validated->fails()) {
                        $errors = $this->parseErrors($validated->errors(), 'translation', $language->name);
                        $this->errors['translations'][$language->name] = $errors;
                    }
                }
                if ($this->errors != []){
                    return response()->json(['message' => $this->errors, 'status' => 'error'], 400);
                }
            }
            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)
                                                        ->join('characteristiccategory', 'characteristiccategory.id', '=', 'characteristicentity.id')
                                                        ->where('characteristiccategory.id', '=', 4)
                                                        ->first();
                    if ($char_enti == null) {
                        return response()->json(['message' => __('messages.no_characteristic_error'), 'status' => 'error'], 400);
                    } elseif ($char_enti != null) {
                        $char_cat = Characteristic_category::where('id', '=', $char_enti->id)
                                                        ->where('id', '=', 4)->first();
                        if ($char_cat == null) {
                            return response()->json(['message' => __('messages.no_category_error'), 'status' => 'error'], 400);
                        }
                    }
                }
            }
            $input_night_spot = $data_night_spot;
            $input_night_spot['updateby'] = $user->id;
            $aux_night_spot = $night_spot;
            $city = City::where('id', '=', $request->city)->first();
            $night_spot->fill($input_night_spot);
            $night_spot->city()->dissociate();
            $night_spot->city()->associate($city);
            $night_spot->save();

            $pictures_already_night_spot = unserialize($night_spot->media);
            $pictures_sent_in_request_already = $request->files_already;
            $pictures_to_delete = [];
            $pictures_to_maintain = [];

            if ($pictures_sent_in_request_already == null) {
                $pictures_to_delete = $pictures_already_night_spot;
            } else {
                foreach ($pictures_already_night_spot as $picture_night_spot) {
                    $band = false;
                    foreach ($pictures_sent_in_request_already as $picture_request) {
                        if ($picture_night_spot == $picture_request) {
                            $band = true;
                        }
                    }
                    if (!$band) {
                        $pictures_to_delete[] = $picture_night_spot;
                    } else {
                        $pictures_to_maintain[] = $picture_night_spot;
                    }
                }
            }
            
            foreach ($pictures_to_delete as $picture_delete) {
                $url = $this->folder.$picture_delete;
                Storage::delete($url);
            }
            $night_spot_images = 0;
            $name_images = [];
            foreach ($pictures_to_maintain as $picture_rename) {
                $info = new \SplFileInfo($picture_rename);
                $file_name = 'night_spot-'.$night_spot->id.'-'.$night_spot_images.'.'.$info->getExtension();
                $url = $this->folder.$picture_rename;
                $new_url = $this->folder.$file_name;
                if ($url != $new_url) {
                    Storage::move($url, $new_url);
                }
                $name_images[] = $file_name;
                $night_spot_images++;
            }

            if (isset($request->file)) {
                if ((count($request->file) + $night_spot_images) > 5) {
                    $night_spot = $aux_night_spot;
                    $night_spot->save();
                    return response()->json(['message' => __('messages.image_maximum_error'), 'status' => 'error'], 400);
                }
                foreach ($request->file as $file) {
                    $name = $file->getClientOriginalName();
                    $file_name = 'night_spot-'.$night_spot->id.'-'.$night_spot_images.'.'.$file->getClientOriginalExtension();
                    $url = $this->folder.$file_name;
                    if (Storage::disk('local')->exists($url)) {
                        Storage::delete($url);
                    }
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(800, 600);
                    $image_resize->save($url);
                    $name_images[] = $file_name;
                    $night_spot_images++;
                } 
            } elseif (!isset($request->file) && count($pictures_already_night_spot) == 0) {
                $night_spot = $aux_night_spot;
                $night_spot->save();
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $media = serialize($name_images);
            $night_spot->media = $media;
            $night_spot->save();

            $translations = Night_spot_translation::where('id', '=', $night_spot->id)->get();
            foreach ($translations as $translation) {
                $translation->delete();
            }

            foreach ($request->translations as $key => $translation) {
                $dataTranslation = [];
                $language = Language::where('name' ,'=', $key)->first();
                foreach ($translation as $key_translation => $translate) {
                    $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                    $data = [
                        'id' => $night_spot->id,
                        'id' => $language->id,
                        'id' => $language_field->id,
                        'content' => $translate
                    ];
                    $translation_night_spot = Night_spot_translation::create($data);
                    $translation_night_spot->nightspot()->associate($night_spot);
                    $night_spot->save();
                }
            }

            $characteristics = Night_spot_characteristic::where('id', '=', $night_spot->id)->get();
            foreach ($characteristics as $characteristic) {
                $characteristic->delete();
            }
            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                    $data = [
                        'id' => $night_spot->id,
                        'id' => $char_enti->id
                    ];
                    $characteristic_night_spot = Night_spot_characteristic::create($data);
                    $characteristic_night_spot->nightspot()->associate($night_spot);
                    $night_spot->save();
                }
            }
            storeLogActivity('night_spot_update', $user->id);
        	return response()->json(['message' => __('messages.night_spot_update_success'), 'status'=> 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.night_spot_update_error'), 'status' => 'error'], 400);
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

        $checkRole = $user->verifyRoleAuthorization($user->id);;

        $checkPermission = $user->verifyPermissionAuthorization('night_spot_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $night_spot = Night_spot::where('id', '=', $id)->first();
                if ($night_spot != null) {
                    if ($night_spot->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $night_spot = $this->night_spots->find($id);
            }

            if ($night_spot == null) {
	    		return response()->json(['message' => __('messages.night_spot_not_found'), 'status' => 'error'], 404);
            }

            $images = unserialize($night_spot->media);
            foreach ($images as $image) {
                $url = $this->folder.$image;
                Storage::delete($url);
            }
            $night_spot->delete();
            storeLogActivity('night_spot_delete', $user->id);
            return response()->json(['message' => __('messages.night_spot_delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.night_spot_delete_error'), 'status' => 'error']);
        }      
    }

    /**
     * Change the status of the night_spot
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);

        $checkRole = $user->verifyRoleAuthorization($user->id);;

        $checkPermission = $user->verifyPermissionAuthorization('night_spot_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $night_spot = Night_spot::where('id', '=', $id)->first();
                if ($night_spot != null) {
                    if ($night_spot->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $night_spot = $this->night_spots->find($id);
            }

            if ($night_spot == NULL) {
	    		return response()->json(['message' => __('messages.night_spot_not_found'), 'status' => 'error'], 404);
            }

            if ($night_spot->status == 0) {
                $night_spot->status = 1;
            } elseif ($night_spot->status == 1) {
                $night_spot->status = 0;
            }
            $night_spot->save();
            storeLogActivity('night_spot_update_status', $user->id);
            return response()->json(['message' => __('messages.night_spot_update_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.night_spot_update_error'), 'status' => 'error']);
        }      
    }

    /**
     * Change the outstanding of the night_spot
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeOutstanding(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);

        $checkRole = $user->verifyRoleAuthorization($user->id);;

        $checkPermission = $user->verifyPermissionAuthorization('night_spot_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $night_spot = Night_spot::where('id', '=', $id)->first();
                if ($night_spot != null) {
                    if ($night_spot->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $night_spot = $this->night_spots->find($id);
            }

            if ($night_spot == NULL) {
	    		return response()->json(['message' => __('messages.night_spot_not_found'), 'status' => 'error'], 404);
            }

            if ($night_spot->outstanding == 0) {
                $night_spot->outstanding = 1;
            } elseif ($night_spot->outstanding == 1) {
                $night_spot->outstanding = 0;
            }
            $night_spot->save();
            storeLogActivity('night_spot_update_outstanding', $user->id);
            return response()->json(['message' => __('messages.night_spot_update_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.night_spot_update_error'), 'status' => 'error']);
        }      
    }
    
    public function getNightSpotsBySlug(Request $request, $slug)
    {
        $night_spots = $this->night_spots->formatNightSpot()->bySlug($slug)->get();
        $night_spots = $this->night_spots->parseNightSpotToFront($night_spots);
        if (count($night_spots) == 0) {
            return response()->json(['message' => __('messages.night_spots_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $night_spots, 'status' => 'success'], 200); 
    }

    public function getNightSpotsByCity(Request $request, $city)
    {
        $night_spots = $this->night_spots->formatNightSpot()->byCity($city)->get();
        $night_spots = $this->night_spots->parseNightSpotToFront($night_spots);
        if (count($night_spots) == 0) {
            return response()->json(['message' => __('messages.night_spots_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $night_spots, 'status' => 'success'], 200); 
    }

    public function searchNightSpots(Request $request)
    {
        $types = null;

        if (isset($request->city)) {
            $city = explode(',', $request->city);
            $city = array_shift($city);
            $getCityBySlug = City::bySlug($city)->first();
            if (!$getCityBySlug) {
                return response()->json(['message' => __('messages.night_spot_not_found'), 'status' => 'error'], 404);
            }
        } else {
            return response()->json(['message' => __('messages.night_spot_not_found'), 'status' => 'error'], 404);
        }
        
        if (isset($request->types)) {
            $types = explode(',', $request->types);
        }

        $night_spots = $this->night_spots->byCharacteristics($types)->byCity($getCityBySlug->slug)->formatNightSpot()->distinct()->get();

        $all_night_spots = $this->night_spots->byCity($city)->inRandomOrder()->formatNightSpot()->get();
        $new_night_spots = [];
        foreach ($night_spots as $night_spot) {
            $new_night_spots[] = $night_spot;
        }

        foreach ($all_night_spots as $all_night_spot) {
            $band = false;
            foreach ($new_night_spots as $new_night_spot) {
                if ($all_night_spot['id'] == $new_night_spot['id']) {
                    $band = true;
                }
            }
            if (!$band) {
                $new_night_spots[] = $all_night_spot;
            }
        } 

        if ($request->language) {
            $new_night_spots = $this->night_spots->parseNightSpotToFront($new_night_spots, $request->language);
        } else {
            $new_night_spots = $this->night_spots->parseNightSpotToFront($new_night_spots);
        }
        if (count($new_night_spots) == 0) {
            return response()->json(['message' => __('messages.night_spot_not_found'), 'status' => 'error'], 404);
        }
        if (floatval($getCityBySlug->latitude) && floatval($getCityBySlug->longitude)) {
            for ($i = 0; $i < count($new_night_spots); $i++) {
                $new_night_spots[$i]['distance'] = calculateDistance($new_night_spots[$i]['location']['latitude'], $new_night_spots[$i]['location']['longitude'], $getCityBySlug->latitude, $getCityBySlug->longitude);
            }
        }

        if (isset($request->sort)) {
            if ($request->sort == 'name') {
                if ($request->order == 'desc') {
                    arraySortBy($new_night_spots, 'name', SORT_DESC);
                } else {
                    arraySortBy($new_night_spots, 'name');
                }
            } elseif ($request->sort == 'distance') {
                if ($request->order == 'desc') {
                    arraySortBy($new_night_spots, 'distance', SORT_DESC);
                } else {
                    arraySortBy($new_night_spots, 'distance');
                }
            } elseif ($request->sort == 'valoration') {
            }
        }
        return response()->json(['message' => $new_night_spots, 'status' => 'success'], 200);
    }

    public function fetchComments($id)
    {
        $night_spot = $this->night_spots->where('id', '=', $id)->get();
        if ($night_spot->isEmpty()) {
            return response()->json(['message' => __('messages.night_spot_not_found'),'status' => 'error'], 404);
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
            $night_spot = $this->night_spots->where('id', '=', $id)->first();
            $comments_with_trash = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->onlyTrashed()->first();
            $comments_without_trash = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->first();
            if ($comments_with_trash != NULL && $comments_without_trash == NULL) {
                $comments_with_trash->restore();
                $data['status'] = 0;
                $comments_with_trash->fill($data);
                $comments_with_trash->nightSpot()->dissociate();
                $comments_with_trash->user()->dissociate();
                $comments_with_trash->nightSpot()->associate($night_spot);
                $comments_with_trash->user()->associate($user);
                $comments_with_trash->save();
                return response()->json(['message' => __('messages.comment_create_success'), 'status' => 'success'], 201);
            } else if ($comments_with_trash == NULL && $comments_without_trash != NULL)  {
                return response()->json(['message' => __('messages.comment_duplicate'), 'status' => 'error'], 400);
            } else {
                $comment = $this->valorations->create($data);
                $comment->fill($data);
                $comment->nightSpot()->associate($night_spot);
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
            $night_spot = $this->night_spots->where('id', '=', $id)->first();
            $comment->fill($data);
            $comment->nightSpot()->dissociate();
            $comment->user()->dissociate();
            $comment->nightSpot()->associate($night_spot);
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
