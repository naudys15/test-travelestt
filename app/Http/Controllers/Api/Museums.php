<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\Language;
use Travelestt\Models\Language_field;
use Travelestt\Models\Characteristic_entity;
use Travelestt\Models\Characteristic_category;
use Travelestt\Models\City;
use Travelestt\Models\Museum;
use Travelestt\Models\Museum_translation;
use Travelestt\Models\Museum_characteristic;
use Travelestt\Models\Museum_valoration;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Museums extends Controller
{
    public function __construct(Museum $museums, Museum_valoration $valorations, APILogin $api_login)
    {
        $this->museums = $museums;
        $this->valorations = $valorations;
        $this->folder = 'assets/images/museums/';
        $this->check_user = $api_login;
        $this->errors = [];
    }

    protected function validatorMuseum(array $data, $id = null)
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
            'slug' => 'required|string|unique:museum,slug,{$id},id,deleted,NULL',
            'latitude' => 'required',
            'longitude' => 'required',
            'phonenumber' => 'nullable',
            // 'duration_value' => 'required|integer',
            // 'id' => 'required|exists:unitduration,id',
            'id' => 'required|exists:city,id',
            // 'status' => 'required|boolean'
        ];

        if ($id) {
            $rules['slug'] = Rule::unique('museum', 'slug')->ignore($id, 'id');
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
            'id' => 'required|exists:museum,id',
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
            'id' => 'required|exists:museum,id',
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
            'id'         =>    'required|exists:museum,id',
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
        if ($type == 'museum') {
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
                    $errors['museum'] = $message;
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
        $museums = $this->museums->formatMuseum()->get();
        $museums = $this->museums->parseMuseumToFront($museums);
        if (count($museums) == 0) {
            return response()->json(['message' => __('messages.museums_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $museums, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('museum_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $data_museum = [
                'slug' => $request->slug,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'phonenumber' => $request->phonenumber,
                // 'duration_value' => $request->duration_value,
                // 'id' => $request->unit,
                'id' => $request->city,
                // 'status' => $request->status
            ];
		    $validated = $this->validatorMuseum($data_museum);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'museum');
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
                            return response()->json(['message' => __('messages.no_language_field_error'), 'status' => 'error'], 400);
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
                                                        ->where('characteristiccategory.id', '=', 3)
                                                        ->first();
                    if ($char_enti == null) {
                        return response()->json(['message' => __('messages.no_characteristic_error'), 'status' => 'error'], 400);
                    } elseif ($char_enti != null) {
                        $char_cat = Characteristic_category::where('id', '=', $char_enti->id)
                                                        ->where('id', '=', 3)->first();
                        if ($char_cat == null) {
                            return response()->json(['message' => __('messages.no_category_error'), 'status' => 'error'], 400);
                        }
                    }
                }
            }
            $input_museum = $data_museum;
            $input_museum['addedby'] = $user->id;
            $input_museum['updateby'] = $user->id;
            $city = City::where('id', '=', $request->city)->first();
            $museum = $this->museums->create($input_museum);
            $museum->city()->associate($city);
            $name_images = [];

            if (isset($request->file)) {
                if (count($request->file) > 5) {
                    return response()->json(['message' => __('messages.image_maximum_error'), 'status' => 'error'], 400);
                }
                $museum_images = 0;
                foreach ($request->file as $file) {
                    $name = $file->getClientOriginalName();
                    $file_name = 'museum-'.$museum->id.'-'.$museum_images.'.'.$file->getClientOriginalExtension();
                    $url = $this->folder.$file_name;
                    if (Storage::disk('local')->exists($url)) {
                        Storage::delete($url);
                    }
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(800, 600);
                    $image_resize->save($url);
                    $name_images[] = $file_name;
                    $museum_images++;
                } 
            } else {
                $museum->delete();
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $media = serialize($name_images);
            $museum->media = $media;
            $museum->save();

            foreach ($request->translations as $key => $translation) {
                $dataTranslation = [];
                $language = Language::where('name' ,'=', $key)->first();
                foreach ($translation as $key_translation => $translate) {
                    $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                    $data = [
                        'id' => $museum->id,
                        'id' => $language->id,
                        'id' => $language_field->id,
                        'content' => $translate
                    ];
                    $translation_museum = Museum_translation::create($data);
                    $translation_museum->museum()->associate($museum);
                    $museum->save();
                }
            }
            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                    $data = [
                        'id' => $museum->id,
                        'id' => $char_enti->id
                    ];
                    $characteristic_museum = Museum_characteristic::create($data);
                    $characteristic_museum->museum()->associate($museum);
                    $museum->save();
                }
            }
            storeLogActivity('museum_create', $user->id);
        	return response()->json(['message' => __('messages.museum_create_success'), 'status' => 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.museum_create_error'), 'status' => 'error'], 400);
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
        $museums = $this->museums->formatMuseum()->where('id', '=', $id)->get();
        $museums = $this->museums->parseMuseumToFront($museums);
        if (count($museums) == 0) {
            return response()->json(['message' => __('messages.museum_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $museums, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('museum_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $museum = Museum::where('id', '=', $id)->first();
                if ($museum != null) {
                    if ($museum->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $museum = Museum::find($id);
            }
            
            if ($museum == null) {
                return response()->json(['message' => __('messages.museum_not_found'), 'status' => 'error'], 404);
            }
            $pictures_already_museum = unserialize($museum->media);
            if (count($pictures_already_museum) == 0 && !$request->hasFile('file')) {
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $data_museum = [
                'slug' => $request->slug,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'phonenumber' => $request->phonenumber,
                // 'duration_value' => $request->duration_value,
                // 'id' => $request->unit,
                'id' => $request->city,
                // 'status' => $request->status
            ];
		    $validated = $this->validatorMuseum($data_museum, $id);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'museum');
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
                            return response()->json(['message' => __('messages.no_language_field_error'), 'status' => 'error'], 400);
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
                                                        ->where('characteristiccategory.id', '=', 3)
                                                        ->first();
                    if ($char_enti == null) {
                        return response()->json(['message' => __('messages.no_characteristic_error'), 'status' => 'error'], 400);
                    } elseif ($char_enti != null) {
                        $char_cat = Characteristic_category::where('id', '=', $char_enti->id)
                                                        ->where('id', '=', 3)->first();
                        if ($char_cat == null) {
                            return response()->json(['message' => __('messages.no_category_error'), 'status' => 'error'], 400);
                        }
                    }
                }
            }
            $input_museum = $data_museum;
            $input_museum['updateby'] = $user->id;
            $aux_museum = $museum;
            $city = City::where('id', '=', $request->city)->first();
            $museum->fill($input_museum);
            $museum->city()->dissociate();
            $museum->city()->associate($city);
            $museum->save();

            $pictures_already_museum = unserialize($museum->media);
            $pictures_sent_in_request_already = $request->files_already;
            $pictures_to_delete = [];
            $pictures_to_maintain = [];

            if ($pictures_sent_in_request_already == null) {
                $pictures_to_delete = $pictures_already_museum;
            } else {
                foreach ($pictures_already_museum as $picture_museum) {
                    $band = false;
                    foreach ($pictures_sent_in_request_already as $picture_request) {
                        if ($picture_museum == $picture_request) {
                            $band = true;
                        }
                    }
                    if (!$band) {
                        $pictures_to_delete[] = $picture_museum;
                    } else {
                        $pictures_to_maintain[] = $picture_museum;
                    }
                }
            }
            
            foreach ($pictures_to_delete as $picture_delete) {
                $url = $this->folder.$picture_delete;
                Storage::delete($url);
            }
            $museum_images = 0;
            $name_images = [];
            foreach ($pictures_to_maintain as $picture_rename) {
                $info = new \SplFileInfo($picture_rename);
                $file_name = 'museum-'.$museum->id.'-'.$museum_images.'.'.$info->getExtension();
                $url = $this->folder.$picture_rename;
                $new_url = $this->folder.$file_name;
                if ($url != $new_url) {
                    Storage::move($url, $new_url);
                }
                $name_images[] = $file_name;
                $museum_images++;
            }

            if (isset($request->file)) {
                if ((count($request->file) + $museum_images) > 5) {
                    $museum = $aux_museum;
                    $museum->save();
                    return response()->json(['message' => __('messages.image_maximum_error'), 'status' => 'error'], 400);
                }
                foreach ($request->file as $file) {
                    $name = $file->getClientOriginalName();
                    $file_name = 'museum-'.$museum->id.'-'.$museum_images.'.'.$file->getClientOriginalExtension();
                    $url = $this->folder.$file_name;
                    if (Storage::disk('local')->exists($url)) {
                        Storage::delete($url);
                    }
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(800, 600);
                    $image_resize->save($url);
                    $name_images[] = $file_name;
                    $museum_images++;
                } 
            } elseif (!isset($request->file) && count($pictures_already_museum) == 0) {
                $museum = $aux_museum;
                $museum->save();
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $media = serialize($name_images);
            $museum->media = $media;
            $museum->save();

            $translations = Museum_translation::where('id', '=', $museum->id)->get();
            foreach ($translations as $translation) {
                $translation->delete();
            }

            foreach ($request->translations as $key => $translation) {
                $dataTranslation = [];
                $language = Language::where('name' ,'=', $key)->first();
                foreach ($translation as $key_translation => $translate) {
                    $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                    $data = [
                        'id' => $museum->id,
                        'id' => $language->id,
                        'id' => $language_field->id,
                        'content' => $translate
                    ];
                    $translation_museum = Museum_translation::create($data);
                    $translation_museum->museum()->associate($museum);
                    $museum->save();
                }
            }

            $characteristics = Museum_characteristic::where('id', '=', $museum->id)->get();
            foreach ($characteristics as $characteristic) {
                $characteristic->delete();
            }
            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                    $data = [
                        'id' => $museum->id,
                        'id' => $char_enti->id
                    ];
                    $characteristic_museum = Museum_characteristic::create($data);
                    $characteristic_museum->museum()->associate($museum);
                    $museum->save();
                }
            }
            storeLogActivity('museum_update', $user->id);
        	return response()->json(['message' => __('messages.museum_update_success'), 'status'=> 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.museum_update_error'), 'status' => 'error'], 400);
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

        $checkPermission = $user->verifyPermissionAuthorization('museum_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $museum = Museum::where('id', '=', $id)->first();
                if ($museum != null) {
                    if ($museum->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $museum = $this->museums->find($id);
            }

            if ($museum == null) {
	    		return response()->json(['message' => __('messages.museum_not_found'), 'status' => 'error'], 404);
            }

            $images = unserialize($museum->media);
            foreach ($images as $image) {
                $url = $this->folder.$image;
                Storage::delete($url);
            }
            $museum->delete();
            storeLogActivity('museum_delete', $user->id);
            return response()->json(['message' => __('messages.museum_delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.museum_delete_error'), 'status' => 'error']);
        }      
    }

    /**
     * Change the status of the museum
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);

        $checkRole = $user->verifyRoleAuthorization($user->id);;

        $checkPermission = $user->verifyPermissionAuthorization('museum_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $museum = Museum::where('id', '=', $id)->first();
                if ($museum != null) {
                    if ($museum->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $museum = $this->museums->find($id);
            }

            if ($museum == null) {
	    		return response()->json(['message' => __('messages.museum_not_found'), 'status' => 'error'], 404);
            }

            if ($museum->status == 0) {
                $museum->status = 1;
            } elseif ($museum->status == 1) {
                $museum->status = 0;
            }
            $museum->save();
            storeLogActivity('museum_update_status', $user->id);
            return response()->json(['message' => __('messages.museum_update_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.museum_update_error'), 'status' => 'error']);
        }      
    }

    /**
     * Change the outstanding of the museum
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeOutstanding(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);

        $checkRole = $user->verifyRoleAuthorization($user->id);;

        $checkPermission = $user->verifyPermissionAuthorization('museum_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $museum = Museum::where('id', '=', $id)->first();
                if ($museum != null) {
                    if ($museum->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $museum = $this->museums->find($id);
            }

            if ($museum == null) {
	    		return response()->json(['message' => __('messages.museum_not_found'), 'status' => 'error'], 404);
            }

            if ($museum->outstanding == 0) {
                $museum->outstanding = 1;
            } elseif ($museum->outstanding == 1) {
                $museum->outstanding = 0;
            }
            $museum->save();
            storeLogActivity('museum_update_outstanding', $user->id);
            return response()->json(['message' => __('messages.museum_update_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.museum_update_error'), 'status' => 'error']);
        }      
    }

    public function getMuseumsBySlug(Request $request, $slug)
    {
        $museums = $this->museums->formatMuseum()->bySlug($slug)->get();
        $museums = $this->museums->parseMuseumToFront($museums);
        if (count($museums) == 0) {
            return response()->json(['message' => __('messages.museums_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $museums, 'status' => 'success'], 200); 
    }

    public function getMuseumsByCity(Request $request, $city)
    {
        $museums = $this->museums->formatMuseum()->byCity($city)->get();
        $museums = $this->museums->parseMuseumToFront($museums);
        if (count($museums) == 0) {
            return response()->json(['message' => __('messages.museums_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $museums, 'status' => 'success'], 200); 
    }

    public function searchMuseums(Request $request)
    {
        $types = null;

        if (isset($request->city)) {
            $city = explode(',', $request->city);
            $city = array_shift($city);
            $getCityBySlug = City::bySlug($city)->first();
            if (!$getCityBySlug) {
                return response()->json(['message' => __('messages.museum_not_found'), 'status' => 'error'], 404);
            }
        } else {
            return response()->json(['message' => __('messages.museum_not_found'), 'status' => 'error'], 404);
        }
        
        if (isset($request->types)) {
            $types = explode(',', $request->types);
        }

        $museums = $this->museums->byCharacteristics($types)->byCity($getCityBySlug->slug)->formatMuseum()->distinct()->get();

        $all_museums = $this->museums->byCity($city)->inRandomOrder()->formatMuseum()->get();
        $new_museums = [];
        foreach ($museums as $museum) {
            $new_museums[] = $museum;
        }

        foreach ($all_museums as $all_museum) {
            $band = false;
            foreach ($new_museums as $new_museum) {
                if ($all_museum['id'] == $new_museum['id']) {
                    $band = true;
                }
            }
            if (!$band) {
                $new_museums[] = $all_museum;
            }
        } 

        if ($request->language) {
            $new_museums = $this->museums->parseMuseumToFront($new_museums, $request->language);
        } else {
            $new_museums = $this->museums->parseMuseumToFront($new_museums);
        }
        if (count($new_museums) == 0) {
            return response()->json(['message' => __('messages.museum_not_found'), 'status' => 'error'], 404);
        }
        if (floatval($getCityBySlug->latitude) && floatval($getCityBySlug->longitude)) {
            for ($i = 0; $i < count($new_museums); $i++) {
                $new_museums[$i]['distance'] = calculateDistance($new_museums[$i]['location']['latitude'], $new_museums[$i]['location']['longitude'], $getCityBySlug->latitude, $getCityBySlug->longitude);
            }
        }

        if (isset($request->sort)) {
            if ($request->sort == 'name') {
                if ($request->order == 'desc') {
                    arraySortBy($new_museums, 'name', SORT_DESC);
                } else {
                    arraySortBy($new_museums, 'name');
                }
            } elseif ($request->sort == 'distance') {
                if ($request->order == 'desc') {
                    arraySortBy($new_museums, 'distance', SORT_DESC);
                } else {
                    arraySortBy($new_museums, 'distance');
                }
            } elseif ($request->sort == 'valoration') {
            }
        }
        return response()->json(['message' => $new_museums, 'status' => 'success'], 200);
    }

    public function fetchComments($id)
    {
        $museum = $this->museums->where('id', '=', $id)->get();
        if ($museum->isEmpty()) {
            return response()->json(['message' => __('messages.museum_not_found'),'status' => 'error'], 404);
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
            $museum = $this->museums->where('id', '=', $id)->first();
            $comments_with_trash = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->onlyTrashed()->first();
            $comments_without_trash = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->first();
            if ($comments_with_trash != NULL && $comments_without_trash == NULL) {
                $comments_with_trash->restore();
                $data['status'] = 0;
                $comments_with_trash->fill($data);
                $comments_with_trash->museum()->dissociate();
                $comments_with_trash->user()->dissociate();
                $comments_with_trash->museum()->associate($museum);
                $comments_with_trash->user()->associate($user);
                $comments_with_trash->save();
                return response()->json(['message' => __('messages.comment_create_success'), 'status' => 'success'], 201);
            } else if ($comments_with_trash == NULL && $comments_without_trash != NULL)  {
                return response()->json(['message' => __('messages.comment_duplicate'), 'status' => 'error'], 400);
            } else {
                $comment = $this->valorations->create($data);
                $comment->fill($data);
                $comment->museum()->associate($museum);
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
            $museum = $this->museums->where('id', '=', $id)->first();
            $comment->fill($data);
            $comment->museum()->dissociate();
            $comment->user()->dissociate();
            $comment->museum()->associate($museum);
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
