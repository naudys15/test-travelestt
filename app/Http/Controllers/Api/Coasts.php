<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\Language;
use Travelestt\Models\Language_field;
use Travelestt\Models\Characteristic_entity;
use Travelestt\Models\Characteristic_category;
use Travelestt\Models\City;
use Travelestt\Models\Coast;
use Travelestt\Models\Coast_translation;
use Travelestt\Models\Coast_characteristic;
use Travelestt\Models\Coast_valoration;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Coasts extends Controller
{
    public function __construct(Coast $coasts, Coast_valoration $valorations, APILogin $api_login)
    {
        $this->coasts = $coasts;
        $this->valorations = $valorations;
        $this->folder = 'assets/images/coasts/';
        $this->check_user = $api_login;
        $this->errors = [];
    }

    protected function validatorCoast(array $data, $id = null)
    {
        $messages = [
            'slug.required' => __('messages.slug_required'),
            'slug.string' => __('messages.slug_string'),
            'slug.unique' => __('messages.slug_unique'),
            'latitude.required' => __('messages.latitude_required'),
            'longitude.required' => __('messages.longitude_required'),
            // 'type_sand.required' => __('messages.type_sand_required'),
            // 'type_sand.integer' => __('messages.type_sand_integer'),
            // 'type_sand.between' => __('messages.type_sand_between'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
            // 'status.required' => __('messages.status_required'),
            // 'status.boolean' => __('messages.status_boolean')
        ];
    	$rules = [
            'slug' => 'required|string|unique:coast,slug,{$id},id,deleted,NULL',
            'latitude' => 'required',
            'longitude' => 'required',
            // 'type_sand' => 'required|integer|between:1,6',
            'id' => 'required|exists:city,id',
            // 'status' => 'required|boolean'
        ];

        if ($id) {
            $rules['slug'] = Rule::unique('coast', 'slug')->ignore($id, 'id')->withTrashed();
        }

    	return Validator::make($data, $rules, $messages);
    }

    protected function validatorCharacteristic(array $data)
    {
        $messages = [
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists')
        ];
    	$rules = [
            'id' => 'required|exists:coast,id',
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
            'id' => 'required|exists:coast,id',
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
            'id'         =>    'required|exists:coast,id',
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
        if ($type == 'coast') {
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
                // foreach ($validated_errors->get('type_sand') as $message) {
                //     $errors['type_sand'] = $message;
                // }
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
                    $errors['coast'] = $message;
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
            $coasts = $this->coasts->offset($offset)->limit($limit)->formatCoast()->get();
        } else {
            $coasts = $this->coasts->formatCoast()->get();
        }
        $coasts = $this->coasts->parseCoastToFront($coasts);
        if (count($coasts) == 0) {
            return response()->json(['message' => __('messages.coasts_not_found'),'status' => 'error'], 404);
        }
        return response()->json(['message' => $coasts, 'status' => 'success'], 200);
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
        
        $checkPermission = $user->verifyPermissionAuthorization('coast_create', ['self', 'all']);
        
        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $data_coast = [
                'slug' => $request->slug,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'id' => $request->city,
            ];
		    $validated = $this->validatorCoast($data_coast);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'coast');
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
                                                        ->where('characteristiccategory.id', '=', 1)
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
            $input_coast = $data_coast;
            $input_coast['addedby'] = $user->id;
            $input_coast['updateby'] = $user->id;
            $city = City::where('id', '=', $request->city)->first();
            $coast = $this->coasts->create($input_coast);
            $coast->city()->associate($city);
            $name_images = [];

            if (isset($request->file)) {
                if (count($request->file) > 5) {
                    return response()->json(['message' => __('messages.image_maximum_error'), 'status' => 'error'], 400);
                }
                $coast_images = 0;
                foreach ($request->file as $file) {
                    $name = $file->getClientOriginalName();
                    $file_name = 'coast-'.$coast->id.'-'.$coast_images.'.'.$file->getClientOriginalExtension();
                    $url = $this->folder.$file_name;
                    if (Storage::disk('local')->exists($url)) {
                        Storage::delete($url);
                    }
                    $image_resize = Image::make($file->getRealPath());
                    $image_resize->resize(800, 600);
                    $image_resize->save($url);
                    $name_images[] = $file_name;
                    $coast_images++;
                }
            } else {
                $coast->delete();
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $media = serialize($name_images);
            $coast->media = $media;
            $coast->save();

            foreach ($request->translations as $key => $translation) {
                $dataTranslation = [];
                $language = Language::where('name' ,'=', $key)->first();
                foreach ($translation as $key_translation => $translate) {
                    $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                    $data = [
                        'id' => $coast->id,
                        'id' => $language->id,
                        'id' => $language_field->id,
                        'content' => $translate
                    ];
                    $translation_coast = Coast_translation::create($data);
                    $translation_coast->coast()->associate($coast);
                    $coast->save();
                }
            }

            foreach ($request->characteristics as $characteristic) {
                $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                $data = [
                    'id' => $coast->id,
                    'id' => $char_enti->id
                ];
                $characteristic_coast = Coast_characteristic::create($data);
                $characteristic_coast->coast()->associate($coast);
                $coast->save();
            }
            storeLogActivity('coast_create', $user->id);
        	return response()->json(['message' => __('messages.coast_create_success'), 'status'=> 'success'], 201);

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.coast_create_error'), 'status' => 'error']);
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
        $coasts = $this->coasts->formatCoast()->where('id', '=', $id)->get();
        $coasts = $this->coasts->parseCoastToFront($coasts);
        if (count($coasts) == 0) {
            return response()->json(['message' => __('messages.coast_not_found'),'status' => 'error'], 404);
        }

        return response()->json(['message' => $coasts, 'status' => 'success'], 200);
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
        
        $checkPermission = $user->verifyPermissionAuthorization('coast_update', ['self', 'all']);
        
        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $coast = Coast::where('id', '=', $id)->first();
                if ($coast != null) {
                    if ($coast->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $coast = Coast::find($id);
            }

            if ($coast == null) {
                return response()->json(['message' => __('messages.coast_not_found'), 'status' => 'error'], 404);
            }
            $pictures_already_coast = unserialize($coast->media);
            if (count($pictures_already_coast) == 0 && !$request->hasFile('file')) {
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $data_coast = [
                'slug' => $request->slug,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'id' => $request->city,
            ];
		    $validated = $this->validatorCoast($data_coast, $id);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors(), 'coast');
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
                                                        ->where('characteristiccategory.id', '=', 1)
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
            $user = $this->check_user->getUserAuthenticated($request->token);
            $input_coast = $data_coast;
            $input_coast['updateby'] = $user->id;
            $aux_coast = $coast;
            $city = City::where('id', '=', $request->city)->first();
            $coast->fill($input_coast);
            $coast->city()->dissociate();
            $coast->city()->associate($city);
            $coast->save();

            $pictures_already_coast = unserialize($coast->media);
            $pictures_sent_in_request_already = $request->files_already;
            $pictures_to_delete = [];
            $pictures_to_maintain = [];

            if ($pictures_sent_in_request_already == null) {
                $pictures_to_delete = $pictures_already_coast;
            } else {
                foreach ($pictures_already_coast as $picture_coast) {
                    $band = false;
                    foreach ($pictures_sent_in_request_already as $picture_request) {
                        if ($picture_coast == $picture_request) {
                            $band = true;
                        }
                    }
                    if (!$band) {
                        $pictures_to_delete[] = $picture_coast;
                    } else {
                        $pictures_to_maintain[] = $picture_coast;
                    }
                }
            }

            foreach ($pictures_to_delete as $picture_delete) {
                $url = $this->folder.$picture_delete;
                Storage::delete($url);
            }
            $coast_images = 0;
            $name_images = [];
            foreach ($pictures_to_maintain as $picture_rename) {
                $info = new \SplFileInfo($picture_rename);
                $file_name = 'coast-'.$coast->id.'-'.$coast_images.'.'.$info->getExtension();
                $url = $this->folder.$picture_rename;
                $new_url = $this->folder.$file_name;
                if ($url != $new_url) {
                    Storage::move($url, $new_url);
                }
                $name_images[] = $file_name;
                $coast_images++;
            }

            if (isset($request->file)) {
                if ((count($request->file) + $coast_images) > 5) {
                    $coast = $aux_coast;
                    $coast->save();
                    return response()->json(['message' => __('messages.image_maximum_error'), 'status' => 'error'], 400);
                }
                foreach ($request->file as $file) {
                    if (!empty($file)) {
                        $name = $file->getClientOriginalName();
                        $file_name = 'coast-'.$coast->id.'-'.$coast_images.'.'.$file->getClientOriginalExtension();
                        $url = $this->folder.$file_name;
                        if (Storage::disk('local')->exists($url)) {
                            Storage::delete($url);
                        }
                        $image_resize = Image::make($file->getRealPath());
                        $image_resize->resize(800, 600);
                        $image_resize->save($url);
                        $name_images[] = $file_name;
                        $coast_images++;
                    }
                }
            } else if (count($pictures_sent_in_request_already) == 0) {
                $coast = $aux_coast;
                $coast->save();
                return response()->json(['message' => __('messages.no_image_error'), 'status' => 'error'], 400);
            }

            $media = serialize($name_images);
            $coast->media = $media;
            $coast->save();

            $translations = Coast_translation::where('id', '=', $coast->id)->get();
            foreach ($translations as $translation) {
                $translation->delete();
            }

            foreach ($request->translations as $key => $translation) {
                $dataTranslation = [];
                $language = Language::where('name' ,'=', $key)->first();
                foreach ($translation as $key_translation => $translate) {
                    $language_field = Language_field::where('name' ,'=', $key_translation)->first();
                    $data = [
                        'id' => $coast->id,
                        'id' => $language->id,
                        'id' => $language_field->id,
                        'content' => $translate
                    ];
                    $translation_coast = Coast_translation::create($data);
                    $translation_coast->coast()->associate($coast);
                    $coast->save();
                }
            }

            $characteristics = Coast_characteristic::where('id', '=', $coast->id)->get();
            foreach ($characteristics as $characteristic) {
                $characteristic->delete();
            }

            foreach ($request->characteristics as $characteristic) {
                $char_enti = Characteristic_entity::where('name' ,'=', $characteristic)->first();
                $data = [
                    'id' => $coast->id,
                    'id' => $char_enti->id
                ];
                $characteristic_coast = Coast_characteristic::create($data);
                $characteristic_coast->coast()->associate($coast);
                $coast->save();
            }
            storeLogActivity('coast_update', $user->id);
        	return response()->json(['message' => __('messages.coast_update_success'), 'status'=> 'success'], 201);

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.coast_update_error'), 'status' => 'error']);
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
        
        $checkPermission = $user->verifyPermissionAuthorization('coast_update', ['self', 'all']);
        
        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $coast = Coast::where('id', '=', $id)->first();
                if ($coast != null) {
                    if ($coast->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $coast = $this->coasts->find($id);
            }

            if ($coast == null) {
	    		return response()->json(['message' => __('messages.coast_not_found'), 'status' => 'error'], 404);
            }

            $images = unserialize($coast->media);
            foreach ($images as $image) {
                $url = $this->folder.$image;
                Storage::delete($url);
            }
            $coast->delete();
            storeLogActivity('coast_delete', $user->id);
            return response()->json(['message' => __('messages.coast_delete_success'), 'status' => 'success'], 200);

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.coast_delete_error'), 'status' => 'error']);
        }
    }

    /**
     * Change the status of the coast
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);

        $checkRole = $user->verifyRoleAuthorization($user->id);;

        $checkPermission = $user->verifyPermissionAuthorization('coast_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $coast = Coast::where('id', '=', $id)->first();
                if ($coast != null) {
                    if ($coast->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $coast = $this->coasts->find($id);
            }

            if ($coast == NULL) {
	    		return response()->json(['message' => __('messages.coast_not_found'), 'status' => 'error'], 404);
            }

            if ($coast->status == 0) {
                $coast->status = 1;
            } elseif ($coast->status == 1) {
                $coast->status = 0;
            }
            $coast->save();
            storeLogActivity('coast_update_status', $user->id);
            return response()->json(['message' => __('messages.coast_update_success'), 'status' => 'success'], 200);

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.coast_update_error'), 'status' => 'error']);
        }
    }

    /**
     * Change the outstanding of the coast
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeOutstanding(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);

        $checkRole = $user->verifyRoleAuthorization($user->id);;
        
        $checkPermission = $user->verifyPermissionAuthorization('coast_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            if ($checkPermission == 'self') {
                $coast = Coast::where('id', '=', $id)->first();
                if ($coast != null) {
                    if ($coast->addedby != $user->id) {
                        return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
                    }
                }
            } elseif ($checkPermission == 'all') {
                $coast = $this->coasts->find($id);
            }

            if ($coast == NULL) {
	    		return response()->json(['message' => __('messages.coast_not_found'), 'status' => 'error'], 404);
            }

            if ($coast->outstanding == 0) {
                $coast->outstanding = 1;
            } else if ($coast->outstanding == 1) {
                $coast->outstanding = 0;
            }
            $coast->save();
            storeLogActivity('coast_update_outstanding', $user->id);
            return response()->json(['message' => __('messages.coast_update_success'), 'status' => 'success'], 200);

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.coast_update_error'), 'status' => 'error']);
        }
    }

    public function getCoastsBySlug(Request $request, $slug)
    {
        $coasts = $this->coasts->formatCoast()->bySlug($slug)->get();
        $coasts = $this->coasts->parseCoastToFront($coasts);
        if (count($coasts) == 0) {
            return response()->json(['message' => __('messages.coasts_not_found'), 'status' => 'error'], 404);
        }

        return response()->json(['message' => $coasts, 'status' => 'success'], 200);
    }

    public function getCoastsByCity(Request $request, $city)
    {
        $offset = filter_var($request->get('offset'), FILTER_VALIDATE_INT) ? intval($request->get('offset')) : 0;
        $limit = filter_var($request->get('limit'), FILTER_VALIDATE_INT) ? intval($request->get('limit')) : 0;
        if ($limit > 0) {
            $coasts = $this->coasts->offset($offset)->limit($limit)->formatCoast()->byCity($city)->get();
        } else {
            $coasts = $this->coasts->formatCoast()->byCity($city)->get();
        }
        $coasts = $this->coasts->parseCoastToFront($coasts);
        if (count($coasts) == 0) {
            return response()->json(['message' => __('messages.coasts_not_found'), 'status' => 'error'], 404);
        }

        return response()->json(['message' => $coasts, 'status' => 'success'], 200);
    }

    public function searchCoasts(Request $request)
    {
        $offset = filter_var($request->get('offset'), FILTER_VALIDATE_INT) ? intval($request->get('offset')) : 0;
        $limit = filter_var($request->get('limit'), FILTER_VALIDATE_INT) ? intval($request->get('limit')) : 0;
        if (isset($request->city)) {
            $city = explode(',', $request->city);
            $city = array_shift($city);
            $getCityBySlug = City::bySlug($city)->first();
            if (!$getCityBySlug) {
                return response()->json(['message' => __('messages.coasts_not_found'), 'status' => 'error'], 404);
            }
        } else {
            return response()->json(['message' => __('messages.coasts_not_found'), 'status' => 'error'], 404);
        }
        if (isset($request->type_sand)) {
            $type_sand = explode(',', $request->type_sand);
        } else {
            $type_sand = null;
        }
        if (isset($request->extras)) {
            $extras = explode(',', $request->extras);
        } else {
            $extras = null;
        }
        if (isset($request->stamps)) {
            $stamps = explode(',', $request->stamps);
        } else {
            $stamps = null;
        }
        if ($stamps != null && $extras != null && $type_sand != null) {
            $extras = array_merge($extras, $type_sand);
            $extras = array_merge($extras, $stamps);
        } elseif ($stamps == null && $extras != null && $type_sand != null) {
            $extras = array_merge($extras, $type_sand);
        } elseif ($stamps == null && $extras == null && $type_sand != null) {
            $extras = $type_sand;
        } elseif ($stamps != null && $extras != null && $type_sand == null) {
            $extras = array_merge($extras, $stamps);
        } elseif ($stamps != null && $extras == null && $type_sand != null) {
            $extras = array_merge($stamps, $type_sand);
        } elseif ($stamps != null && $extras == null && $type_sand == null) {
            $extras = $stamps;
        }
        $coasts = $this->coasts->byCharacteristics($extras)->byCity($getCityBySlug->slug)->formatCoast()->distinct()->get();
        $all_coasts = $this->coasts->byCity($city)->formatCoast()->inRandomOrder()->get();
        $new_coasts = [];
        foreach ($coasts as $coast) {
            $new_coasts[] = $coast;
        }
        foreach ($all_coasts as $all_coast) {
            $band = false;
            foreach ($new_coasts as $new_coast) {
                if ($all_coast['id'] == $new_coast['id']) {
                    $band = true;
                }
            }
            if (!$band) {
                $new_coasts[] = $all_coast;
            }
        }
        if ($request->language) {
            $new_coasts = $this->coasts->parseCoastToFront($new_coasts, $request->language);
        } else {
            $new_coasts = $this->coasts->parseCoastToFront($new_coasts);
        }
        if (count($new_coasts) == 0) {
            return response()->json(['message' => __('messages.coasts_not_found'), 'status' => 'error'], 404);
        }
        if (floatval($getCityBySlug->latitude) && floatval($getCityBySlug->longitude)) {
            for ($i = 0; $i < count($new_coasts); $i++) {
                $new_coasts[$i]['distance'] = calculateDistance($new_coasts[$i]['location']['latitude'], $new_coasts[$i]['location']['longitude'], $getCityBySlug->latitude, $getCityBySlug->longitude);
            }
        }
        if (isset($request->sort)) {
            if ($request->sort == 'name') {
                if ($request->order == 'desc') {
                    arraySortBy($new_coasts, 'name', SORT_DESC);
                } else {
                    arraySortBy($new_coasts, 'name');
                }
            } elseif ($request->sort == 'distance') {
                if ($request->order == 'desc') {
                    arraySortBy($new_coasts, 'distance', SORT_DESC);
                } else {
                    arraySortBy($new_coasts, 'distance');
                }
            } elseif ($request->sort == 'valoration') {
            }
        }
        if ($limit > 0) {
            $new_coasts = array_slice($new_coasts, $offset, $limit);
        }
        return response()->json(['message' => $new_coasts, 'status' => 'success'], 200);
    }

    public function fetchComments($id)
    {
        $coast = $this->coasts->where('id', '=', $id)->get();
        if ($coast->isEmpty()) {
            return response()->json(['message' => __('messages.coast_not_found'),'status' => 'error'], 404);
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
            $coast = $this->coasts->where('id', '=', $id)->first();
            $comments_with_trash = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->onlyTrashed()->first();
            $comments_without_trash = $this->valorations->where('id', '=', $id)->where('id', '=', $user->id)->first();
            if ($comments_with_trash != NULL && $comments_without_trash == NULL) {
                $comments_with_trash->restore();
                $data['status'] = 0;
                $comments_with_trash->fill($data);
                $comments_with_trash->coast()->dissociate();
                $comments_with_trash->user()->dissociate();
                $comments_with_trash->coast()->associate($coast);
                $comments_with_trash->user()->associate($user);
                $comments_with_trash->save();
                return response()->json(['message' => __('messages.comment_create_success'), 'status' => 'success'], 201);
            } else if ($comments_with_trash == NULL && $comments_without_trash != NULL)  {
                return response()->json(['message' => __('messages.comment_duplicate'), 'status' => 'error'], 400);
            } else {
                $comment = $this->valorations->create($data);
                $comment->fill($data);
                $comment->coast()->associate($coast);
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
                'recaptcha'       =>    $request->recaptcha
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
            $coast = $this->coasts->where('id', '=', $id)->first();
            $comment->fill($data);
            $comment->coast()->dissociate();
            $comment->user()->dissociate();
            $comment->coast()->associate($coast);
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
