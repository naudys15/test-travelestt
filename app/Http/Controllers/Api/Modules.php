<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Travelestt\Models\Entity;
use Travelestt\Models\Module;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

class Modules extends Controller
{
    public function __construct(Module $modules, APILogin $api_login)
    {
        $this->modules = $modules;
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
            'id' => 'required|exists:entity,id',
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
        $modules = $this->modules->formatModule()->get();

        if (!$modules) {
            return response()->json(['message' => __('messages.modules_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $modules, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('module_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataModule = [
                'name' => $request->name,
                'description' => $request->description,
                'id' => $request->entity
            ];
		    $validated = $this->validator($dataModule);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataModule;         

            $module = $this->modules->create($input);
            $entity = Entity::find($request->entity);
            $module->entity()->associate($entity);
            $module->save();
			storeLogActivity('module_create', $user->id);
        	return response()->json(['message' => __('messages.module_create_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.module_create_error'), 'status' => 'error']);
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
        $module = $this->modules->formatModule()->find($id);

        if (!$module) {
            return response()->json(['message' => __('messages.module_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $module, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('module_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataModule = [
                'name' => $request->name,
                'description' => $request->description,
                'id' => $request->entity
            ];
		    $validated = $this->validator($dataModule);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataModule;   
	    	$module = $this->modules->find($id);

            if (!$module) {
	    		return response()->json(['message' => __('messages.module_not_found'), 'status' => 'error'], 404);
	    	}

            $module->fill($input);          
            $entity = Entity::find($request->entity);
            $module->entity()->dissociate();
            $module->entity()->associate($entity);
            $module->save();
			storeLogActivity('module_update', $user->id);
        	return response()->json(['message' => __('messages.module_update_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.module_update_error'), 'status' => 'error']);
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
        $checkPermission = $user->verifyPermissionAuthorization('module_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $module = $this->modules->find($id);

            if (!$module) {
	    		return response()->json(['message' => __('messages.module_not_found'), 'status' => 'error'], 404);
            }

            $module->delete();
            storeLogActivity('module_delete', $user->id);
            return response()->json(['message' => __('messages.module_delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.module_delete_error'), 'status' => 'error']);
        }      
    }
}
