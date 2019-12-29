<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\Entity;
use Travelestt\Models\Characteristic_category;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Characteristic_categories extends Controller
{
    public function __construct(Characteristic_category $categories, APILogin $api_login)
    {
        $this->categories = $categories;
        $this->check_user = $api_login;
    }

    protected function validator(array $data)
    {
        $messages = [
            'name.required' => __('messages.name_required'),
            'name.string' => __('messages.name_string'),
            'description.required' => __('messages.description_required'),
            'description.string' => __('messages.description_string'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
        ];
    	$rules = [
            'name' => 'required|string',
            'description' => 'required|string',
            'id' => 'required|exists:entity,id'
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
            foreach ($validated_errors->get('description') as $message) {
                $errors['description'] = $message;
            }
            foreach ($validated_errors->get('id') as $message) {
                $errors['entity'] = $message;
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
        $categories = $this->categories->formatCategory()->get();

        if (count($categories) == 0) {
            return response()->json(['message' => __('messages.categories_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $categories, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('category_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataCategory = [
                'name' => $request->name,
                'description' => $request->description,
                'id' => $request->entity
            ];
		    $validated = $this->validator($dataCategory);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataCategory;          

            $entity = Entity::find($request->entity);
            $category = $this->categories->create($input);
            $category->entity()->associate($entity);
			storeLogActivity('category_create', $user->id);
        	return response()->json(['message' => __('messages.category_create_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.category_create_error'), 'status' => 'error']);
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
        $category = $this->categories->formatCategory()->find($id);

        if (count($category) == 0) {
            return response()->json(['message' => __('messages.category_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $category, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('category_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataCategory = [
                'name' => $request->name,
                'description' => $request->description,
                'id' => $request->entity
            ];
		    $validated = $this->validator($dataCategory);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataCategory; 
            $category = $this->categories->find($id);

            if (count($category) == 0) {
	    		return response()->json(['message' => __('messages.category_not_found'), 'status' => 'error'], 404);
	    	}

            $category->fill($input); 
            $entity = Entity::find($request->entity);
            $category->entity()->dissociate();
            $category->entity()->associate($entity);         
            $category->save();
			storeLogActivity('category_update', $user->id);
        	return response()->json(['message' => __('messages.category_update_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.category_update_error'), 'status' => 'error']);
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

        $checkPermission = $user->verifyPermissionAuthorization('category_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $category = $this->categories->find($id);

            if (count($category) == 0) {
	    		return response()->json(['message' => __('messages.category_not_found'), 'status' => 'error'], 404);
            }
            
            $category->delete();
            storeLogActivity('category_delete', $user->id);
            return response()->json(['message' => __('messages.category_delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.category_delete_error'), 'status' => 'error']);
        }      
    }
}
