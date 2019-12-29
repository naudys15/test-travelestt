<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\Characteristic_category;
use Travelestt\Models\Characteristic_entity;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Characteristic_entities extends Controller
{
    public function __construct(Characteristic_entity $characteristics, APILogin $api_login)
    {
        $this->characteristics = $characteristics;
        $this->check_user = $api_login;
    }

    protected function validator(array $data)
    {
        $messages = [
            'name.required' => __('messages.name_required'),
            'name.string' => __('messages.name_string'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
        ];
    	$rules = [
            'name' => 'required|string',
            'id' => 'required|exists:characteristiccategory,id'
        ];

    	return Validator::make($data, $rules, $messages);
    }

    protected function parseErrors($validated_errors)
    {
        $errors = [];
        foreach ($validated_errors->all() as $error) {
            foreach ($validated_errors->get('name') as $message) {
                $errors['name'] = $message;
            }
            foreach ($validated_errors->get('id') as $message) {
                $errors['category'] = $message;
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
        $characteristics = $this->characteristics->formatCharacteristicEntity()->get();

        if (count($characteristics) == 0) {
            return response()->json(['message' => __('messages.characteristics_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $characteristics, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('characteristic_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataCharacteristic = [
                'name' => $request->name,
                'id' => $request->category
            ];
		    $validated = $this->validator($dataCharacteristic);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataCharacteristic;           

            $category = Characteristic_category::find($request->category);
            $characteristic = $this->characteristics->create($input);
            $characteristic->category()->associate($category);
			storeLogActivity('characteristic_create', $user->id);
        	return response()->json(['message' => __('messages.characteristic_create_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.characteristic_create_error'), 'status' => 'error']);
    	}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $characteristic = $this->characteristics->formatCharacteristicEntity()->find($id);

        if (count($characteristic) == 0) {
            return response()->json(['message' => __('messages.characteristic_not_found'),'status' => 'error'], 404); 
        }

        return response()->json(['message' => $characteristic, 'status' => 'success'], 200);     
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
        
        $checkRole = $user->verifyRoleAuthorization($user->id);;;
        
        $checkPermission = $user->verifyPermissionAuthorization('characteristic_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataCharacteristic = [
                'name' => $request->name,
                'id' => $request->category
            ];
		    $validated = $this->validator($dataCharacteristic);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataCharacteristic;
            $characteristic = $this->characteristics->find($id);

            if (count($characteristic) == 0) {
	    		return response()->json(['message' => __('messages.categories_not_found'), 'status' => 'error'], 404);
	    	}

            $characteristic->fill($input); 
            $category = Characteristic_category::find($request->category);
            $characteristic->category()->dissociate();
            $characteristic->category()->associate($category);         
            $characteristic->save();
			storeLogActivity('characteristic_update', $user->id);
        	return response()->json(['message' => __('messages.characteristic_update_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.characteristic_update_error'), 'status' => 'error']);
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
        
        $checkRole = $user->verifyRoleAuthorization($user->id);;;

        $checkPermission = $user->verifyPermissionAuthorization('characteristic_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $characteristic = $this->characteristics->find($id);

            if (count($characteristic) == 0) {
	    		return response()->json(['message' => __('messages.categories_not_found'), 'status' => 'error'], 404);
            }
            
            $characteristic->delete();
            storeLogActivity('characteristic_delete', $user->id);
            return response()->json(['message' => __('messages.characteristic_delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.characteristic_delete_error'), 'status' => 'error']);
        }      
    }

    public function getCharacteristicByCategory(Request $request, $id)
    {
        $characteristic = $this->characteristics->byCategory($id)->get();

        if (count($characteristic) == 0) {
            return response()->json(['message' => __('messages.characteristic_not_found'),'status' => 'error'], 404); 
        }

        return response()->json(['message' => $characteristic, 'status' => 'success'], 200);     
    }

    public function getCharacteristicByEntity(Request $request, $name)
    {
        $characteristics = $this->characteristics->byEntity($name)->get();

        if (count($characteristics) == 0) {
            return response()->json(['message' => __('messages.characteristic_not_found'),'status' => 'error'], 404); 
        }
        
        $categories = [];
        $categoriesId = [];
        foreach ($characteristics as $characteristic) {
            if (!in_array($characteristic->category->id, $categoriesId)) {
                $categoriesId[] = $characteristic->category->id;
                $category['id'] = $characteristic->category->id;
                $category['name'] = $characteristic->category->name;
                $category['description'] = $characteristic->category->description;
                $category['render'] = $characteristic->category->render;
                $categories[] = $category;
            }
        }
        for ($i = 0; $i < count($categories); $i++) {
            foreach ($characteristics as $characteristic) {
                if ($characteristic->category->id == $categories[$i]['id']) {
                    $characteristic = [
                        'id'        =>    $characteristic->id,
                        'name'      =>    $characteristic->name
                    ];
                    $categories[$i]['characteristics'][] = $characteristic;
                }
            }
        }
        return response()->json(['message' => $categories, 'status' => 'success'], 200);   
    }
}
