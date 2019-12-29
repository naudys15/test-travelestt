<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\Language;
use Travelestt\Models\Language_field;
use Travelestt\Models\Characteristic_entity;
use Travelestt\Models\Characteristic_category;
use Travelestt\Models\City;
use Travelestt\Models\Festival;
use Travelestt\Models\Festival_translation;
use Travelestt\Models\Festival_characteristic;
use Travelestt\Models\Festival_valoration;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Festivals extends Controller
{
    public function __construct(Festival $festivals, Festival_valoration $valorations, APILogin $api_login)
    {
        $this->festivals = $festivals;
        $this->valorations = $valorations;
        $this->folder = 'assets/images/festivals/';
        $this->check_user = $api_login;
        $this->errors = [];
    }

    protected function validatorFestival(array $data, $id = null)
    {
        $messages = [
            'slug.required' => __('messages.slug_required'),
            'slug.string' => __('messages.slug_string'),
            'slug.unique' => __('messages.slug_unique'),
            'latitude.required' => __('messages.latitude_required'),
            'longitude.required' => __('messages.longitude_required'),
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
            'slug' => 'required|string|unique:festival,slug,{$id},id,deleted,NULL',
            'latitude' => 'required',
            'longitude' => 'required',
            // 'duration_value' => 'required|integer',
            // 'id' => 'required|exists:unitduration,id',
            'id' => 'required|exists:city,id',
            // 'status' => 'required|boolean'
        ];

        if ($id) {
            $rules['slug'] = Rule::unique('festival', 'slug')->ignore($id, 'id');
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
            'id' => 'required|exists:festival,id',
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
            'id' => 'required|exists:festival,id',
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
            'id'         =>    'required|exists:festival,id',
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
        if ($type == 'festival') {
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
        } elseif ($type == 'translation') {
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
                    $errors['festival'] = $message;
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
    public function index(Request $request)
    {
        $offset = filter_var($request->get('offset'), FILTER_VALIDATE_INT) ? intval($request->get('offset')) : 0;
        $limit = filter_var($request->get('limit'), FILTER_VALIDATE_INT) ? intval($request->get('limit')) : 0;
        if ($limit > 0) {
            $festivals = $this->festivals->offset($offset)->limit($limit)->formatFestival()->get();
        } else {
            $festivals = $this->festivals->formatFestival()->get();
        }
        $festivals = $this->festivals->parseFestivalToFront($festivals);
        if (count($festivals) == 0) {
            return response()->json(['message' => __('messages.festivals_not_found'),'status' => 'error'], 404);
        }
        return response()->json(['message' => $festivals, 'status' => 'success'], 200);    
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

        $checkPermission = $user->verifyPermissionAuthorization('festival_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $data_festival = [
                'slug' => $request->slug,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                // 'duration_value' => $request->duration_value,
                // 'id' => $request->unit,
                'id' => $request->city,
                // 'status' => $request->status
            ];
		    $validated = $this->validatorFestival($data_festival);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'festival');
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
                                                        ->where('characteristiccategory.id', '=', 2)
                                                        ->first();
                    if ($char_enti == null) {
                        return response()->json(['message' => __('messages.no_characteristic_error'), 'status' => 'error'], 400);
                    } elseif ($char_enti != null) {
                        $char_cat = Characteristic_category::where('id', '=', $char_enti->id)
                                                        ->where('id', '=', 2)->first();
                        if ($char_cat == null) {
                            return response()->json(['message' => __('messages.no_category_error'), 'status' => 'error'], 400);
                        }
                    }
                }
            }
            
            $input_festival = $data_festival;
            $input_festival['addedby'] = $user->id;
            $input_festival['updateby'] = $user->id;
            $city = City::where('id', '=', $request->city)->first();
            $festival = $this->festivals->create($input_festival);
            $festival->city()->associate($city);
            $name_images = [];

            if (isset($request->file)) {
                if (count($request->file) > 5) {
                    return response()->json(['message' => __('messages.image_maximum_error'), 'status' => 'error'], 400);
                }
                $festival_images = 0;
                foreach ($request->file as $file) {
                    $name = $file->getClientOriginalName();
                    $file_name = 'festival-'.$festival->id.'-'.$festival_images.'.'.$file->getClientOriginalExtension();
                    $url = $this->folder.$file_name;
                    if (Storage::disk('local')->exists($url)) {
                        Storage::delete($url);
                    }
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(800, 600);
                    $image_resize->save($url);
                    $name_images[] = $file_name;
                    $festival_images++;
                } 
            } else {
                $festival->delete();
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $media = serialize($name_images);
            $festival->media = $media;
            $festival->save();

            foreach ($request->translations as $key => $translation) {
                $dataTranslation = [];
                $language = Language::where('name' ,'=', $key)->first();
                foreach ($translation as $key_translation => $translate) {
                    $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                    $data = [
                        'id' => $festival->id,
                        'id' => $language->id,
                        'id' => $language_field->id,
                        'content' => $translate
                    ];
                    $translation_festival = Festival_translation::create($data);
                    $translation_festival->festival()->associate($festival);
                    $festival->save();
                }
            }
            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                    $data = [
                        'id' => $festival->id,
                        'id' => $char_enti->id
                    ];
                    $characteristic_festival = Festival_characteristic::create($data);
                    $characteristic_festival->festival()->associate($festival);
                    $festival->save();
                }
            }
            storeLogActivity('festival_create', $user->id);
        	return response()->json(['message' => __('messages.festival_create_success'), 'status' => 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.festival_create_error'), 'status' => 'error'], 400);
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
        $festivals = $this->festivals->formatFestival()->where('id', '=', $id)->get();
        $festivals = $this->festivals->parseFestivalToFront($festivals);
        if (count($festivals) == 0) {
            return response()->json(['message' => __('messages.festival_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $festivals, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('festival_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $festival = Festival::where('id', '=', $id)->first();
                if ($festival != null) {
                    if ($festival->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $festival = Festival::find($id);
            }

            $festival = Festival::find($id);
            if ($festival == null) {
                return response()->json(['message' => __('messages.festival_not_found'), 'status' => 'error'], 404);
            }
            $pictures_already_festival = unserialize($festival->media);
            if (count($pictures_already_festival) == 0 && !$request->hasFile('file')) {
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $data_festival = [
                'slug' => $request->slug,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                // 'duration_value' => $request->duration_value,
                // 'id' => $request->unit,
                'id' => $request->city,
                // 'status' => $request->status
            ];
		    $validated = $this->validatorFestival($data_festival, $id);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'festival');
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
                                                        ->where('characteristiccategory.id', '=', 2)
                                                        ->first();
                    if ($char_enti == null) {
                        return response()->json(['message' => __('messages.no_characteristic_error'), 'status' => 'error'], 400);
                    } elseif ($char_enti != null) {
                        $char_cat = Characteristic_category::where('id', '=', $char_enti->id)
                                                        ->where('id', '=', 1)->first();
                        if ($char_cat == null) {
                            return response()->json(['message' => __('messages.no_category_error'), 'status' => 'error'], 400);
                        }
                    }
                }
            }
            $input_festival = $data_festival;
            $input_festival['updateby'] = $user->id;
            $aux_festival = $festival;
            $city = City::where('id', '=', $request->city)->first();
            $festival->fill($input_festival);
            $festival->city()->dissociate();
            $festival->city()->associate($city);
            $festival->save();

            $pictures_already_festival = unserialize($festival->media);
            $pictures_sent_in_request_already = $request->files_already;
            $pictures_to_delete = [];
            $pictures_to_maintain = [];

            if ($pictures_sent_in_request_already == null) {
                $pictures_to_delete = $pictures_already_festival;
            } else {
                foreach ($pictures_already_festival as $picture_festival) {
                    $band = false;
                    foreach ($pictures_sent_in_request_already as $picture_request) {
                        if ($picture_festival == $picture_request) {
                            $band = true;
                        }
                    }
                    if (!$band) {
                        $pictures_to_delete[] = $picture_festival;
                    } else {
                        $pictures_to_maintain[] = $picture_festival;
                    }
                }
            }
            
            foreach ($pictures_to_delete as $picture_delete) {
                $url = $this->folder.$picture_delete;
                Storage::delete($url);
            }
            $festival_images = 0;
            $name_images = [];
            foreach ($pictures_to_maintain as $picture_rename) {
                $info = new \SplFileInfo($picture_rename);
                $file_name = 'festival-'.$festival->id.'-'.$festival_images.'.'.$info->getExtension();
                $url = $this->folder.$picture_rename;
                $new_url = $this->folder.$file_name;
                if ($url != $new_url) {
                    Storage::move($url, $new_url);
                }
                $name_images[] = $file_name;
                $festival_images++;
            }

            if (isset($request->file)) {
                if ((count($request->file) + $festival_images) > 5) {
                    $festival = $aux_festival;
                    $festival->save();
                    return response()->json(['message' => __('messages.image_maximum_error'), 'status' => 'error'], 400);
                }
                foreach ($request->file as $file) {
                    $name = $file->getClientOriginalName();
                    $file_name = 'festival-'.$festival->id.'-'.$festival_images.'.'.$file->getClientOriginalExtension();
                    $url = $this->folder.$file_name;
                    if (Storage::disk('local')->exists($url)) {
                        Storage::delete($url);
                    }
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(800, 600);
                    $image_resize->save($url);
                    $name_images[] = $file_name;
                    $festival_images++;
                } 
            } elseif (!isset($request->file) && count($pictures_already_festival) == 0) {
                $festival = $aux_festival;
                $festival->save();
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $media = serialize($name_images);
            $festival->media = $media;
            $festival->save();

            $translations = Festival_translation::where('id', '=', $festival->id)->get();
            foreach ($translations as $translation) {
                $translation->delete();
            }

            foreach ($request->translations as $key => $translation) {
                $dataTranslation = [];
                $language = Language::where('name' ,'=', $key)->first();
                foreach ($translation as $key_translation => $translate) {
                    $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                    $data = [
                        'id' => $festival->id,
                        'id' => $language->id,
                        'id' => $language_field->id,
                        'content' => $translate
                    ];
                    $translation_festival = Festival_translation::create($data);
                    $translation_festival->festival()->associate($festival);
                    $festival->save();
                }
            }

            $characteristics = Festival_characteristic::where('id', '=', $festival->id)->get();
            foreach ($characteristics as $characteristic) {
                $characteristic->delete();
            }
            if (isset($request->characteristics)) {
                foreach ($request->characteristics as $characteristic) {
                    $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                    $data = [
                        'id' => $festival->id,
                        'id' => $char_enti->id
                    ];
                    $characteristic_festival = Festival_characteristic::create($data);
                    $characteristic_festival->festival()->associate($festival);
                    $festival->save();
                }
            }
            storeLogActivity('festival_update', $user->id);
        	return response()->json(['message' => __('messages.festival_update_success'), 'status'=> 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.festival_update_error'), 'status' => 'error'], 400);
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

        $checkPermission = $user->verifyPermissionAuthorization('festival_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $festival = Festival::where('id', '=', $id)->first();
                if ($festival != null) {
                    if ($festival->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $festival = $this->festivals->find($id);
            }

            if ($festival == null) {
	    		return response()->json(['message' => __('messages.festival_not_found'), 'status' => 'error'], 404);
            }

            $images = unserialize($festival->media);
            foreach ($images as $image) {
                $url = $this->folder.$image;
                Storage::delete($url);
            }
            $festival->delete();
            storeLogActivity('festival_delete', $user->id);
            return response()->json(['message' => __('messages.festival_delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.festival_delete_error'), 'status' => 'error']);
        }      
    }

    /**
     * Change the status of the festival
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        
        $checkRole = $user->verifyRoleAuthorization($user->id);;

        $checkPermission = $user->verifyPermissionAuthorization('festival_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $festival = Festival::where('id', '=', $id)->first();
                if ($festival != null) {
                    if ($festival->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $festival = $this->festivals->find($id);
            }

            if ($festival == NULL) {
	    		return response()->json(['message' => __('messages.festival_not_found'), 'status' => 'error'], 404);
            }

            if ($festival->status == 0) {
                $festival->status = 1;
            } elseif ($festival->status == 1) {
                $festival->status = 0;
            }
            $festival->save();
            storeLogActivity('festival_update_status', $user->id);
            return response()->json(['message' => __('messages.festival_update_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.festival_update_error'), 'status' => 'error']);
        }      
    }

        /**
     * Change the outstanding of the festival
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeOutstanding(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        
        $checkRole = $user->verifyRoleAuthorization($user->id);;

        $checkPermission = $user->verifyPermissionAuthorization('festival_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $festival = Festival::where('id', '=', $id)->first();
                if ($festival != null) {
                    if ($festival->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $festival = $this->festivals->find($id);
            }

            if ($festival == NULL) {
	    		return response()->json(['message' => __('messages.festival_not_found'), 'status' => 'error'], 404);
            }

            if ($festival->outstanding == 0) {
                $festival->outstanding = 1;
            } elseif ($festival->outstanding == 1) {
                $festival->outstanding = 0;
            }
            $festival->save();
            storeLogActivity('festival_update_outstanding', $user->id);
            return response()->json(['message' => __('messages.festival_update_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.festival_update_error'), 'status' => 'error']);
        }      
    }

    public function getFestivalsBySlug(Request $request, $slug)
    {
        $festivals = $this->festivals->formatFestival()->bySlug($slug)->get();
        $festivals = $this->festivals->parseFestivalToFront($festivals);
        if (count($festivals) == 0) {
            return response()->json(['message' => __('messages.festivals_not_found'),'status' => 'error'], 404); 
        }

        return response()->json(['message' => $festivals, 'status' => 'success'], 200); 
    }

    public function getFestivalsByCity(Request $request, $city)
    {
        $festivals = $this->festivals->formatFestival()->byCity($city)->get();
        $festivals = $this->festivals->parseFestivalToFront($festivals);
        if (count($festivals) == 0) {
            return response()->json(['message' => __('messages.festivals_not_found'),'status' => 'error'], 404); 
        }

        return response()->json(['message' => $festivals, 'status' => 'success'], 200); 
    }

    public function searchFestivals(Request $request)
    {
        if (isset($request->city)) {
            $city = explode(',', $request->city);
            $city = array_shift($city);
            $getCityBySlug = City::bySlug($city)->first();
            if (!$getCityBySlug) {
                return response()->json(['message' => __('messages.festivals_not_found'), 'status' => 'error'], 404);
            }
        } else {
            return response()->json(['message' => __('messages.festivals_not_found'), 'status' => 'error'], 404);
        }

        $festivals = $this->festivals->byCity($getCityBySlug->slug)->formatFestival()->distinct()->get();

        $all_festivals = $this->festivals->byCity($city)->inRandomOrder()->formatFestival()->get();
        $new_festivals = [];
        foreach ($festivals as $festival) {
            $new_festivals[] = $festival;
        }

        foreach ($all_festivals as $all_festival) {
            $band = false;
            foreach ($new_festivals as $new_festival) {
                if ($all_festival['id'] == $new_festival['id']) {
                    $band = true;
                }
            }
            if (!$band) {
                $new_festivals[] = $all_festival;
            }
        } 

        if ($request->language) {
            $new_festivals = $this->festivals->parseFestivalToFront($new_festivals, $request->language);
        } else {
            $new_festivals = $this->festivals->parseFestivalToFront($new_festivals);
        }
        if (count($new_festivals) == 0) {
            return response()->json(['message' => __('messages.festivals_not_found'), 'status' => 'error'], 404);
        }
        if (floatval($getCityBySlug->latitude) && floatval($getCityBySlug->longitude)) {
            for ($i = 0; $i < count($new_festivals); $i++) {
                $new_festivals[$i]['distance'] = calculateDistance($new_festivals[$i]['location']['latitude'], $new_festivals[$i]['location']['longitude'], $getCityBySlug->latitude, $getCityBySlug->longitude);
            }
        }

        if (isset($request->sort)) {
            if ($request->sort == 'name') {
                if ($request->order == 'desc') {
                    arraySortBy($new_festivals, 'name', SORT_DESC);
                } else {
                    arraySortBy($new_festivals, 'name');
                }
            } elseif ($request->sort == 'distance') {
                if ($request->order == 'desc') {
                    arraySortBy($new_festivals, 'distance', SORT_DESC);
                } else {
                    arraySortBy($new_festivals, 'distance');
                }
            } elseif ($request->sort == 'valoration') {
            }
        }
        return response()->json(['message' => $new_festivals, 'status' => 'success'], 200);
    }

    public function fetchComments($id)
    {
        $coast = $this->festivals->where('id', '=', $id)->get();
        if ($coast->isEmpty()) {
            return response()->json(['message' => __('messages.festival_not_found'),'status' => 'error'], 404);
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
            $coast = $this->festivals->where('id', '=', $id)->first();
            $comments_with_trash = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->onlyTrashed()->first();
            $comments_without_trash = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->first();
            if ($comments_with_trash != NULL && $comments_without_trash == NULL) {
                $comments_with_trash->restore();
                $data['status'] = 0;
                $comments_with_trash->fill($data);
                $comments_with_trash->coast()->dissociate();
                $comments_with_trash->user()->dissociate();
                $comments_with_trash->festival()->associate($coast);
                $comments_with_trash->user()->associate($user);
                $comments_with_trash->save();
                return response()->json(['message' => __('messages.comment_create_success'), 'status' => 'success'], 201);
            } else if ($comments_with_trash == NULL && $comments_without_trash != NULL)  {
                return response()->json(['message' => __('messages.comment_duplicate'), 'status' => 'error'], 400);
            } else {
                $comment = $this->valorations->create($data);
                $comment->fill($data);
                $comment->festival()->associate($coast);
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
            $coast = $this->festivals->where('id', '=', $id)->first();
            $comment->fill($data);
            $comment->coast()->dissociate();
            $comment->user()->dissociate();
            $comment->festival()->associate($coast);
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
