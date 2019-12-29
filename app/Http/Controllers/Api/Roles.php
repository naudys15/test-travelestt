<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Travelestt\Models\Role;
use Travelestt\Models\Permission_role;
use Travelestt\Models\Sub_module;
use Travelestt\Models\Range_sub_module;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

class Roles extends Controller
{
    public function __construct(Role $roles, APILogin $api_login)
    {
        $this->roles = $roles;
        $this->check_user = $api_login;
    }

    protected function validator(array $data)
    {
        $messages = [
            'name.required' => __('messages.name_required'),
            'name.string' => __('messages.name_string'),
            'description.required' => __('messages.description_required'),
            'description.string' => __('messages.description_string')
        ];
    	$rules = [
            'name' => 'required|string',
            'description' => 'required|string',
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
        $roles = $this->roles->formatRole()->get();

        if (!$roles) {
            return response()->json(['message' => __('messages.roles_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $roles, 'status' => 'success'], 200);     
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
        
        $checkRole = $user->verifyRoleAuthorization($user->id);
        $checkPermission = $user->verifyPermissionAuthorization('create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataRole = [
                'name' => $request->name,
                'description' => $request->description
            ];
		    $validated = $this->validator($dataRole);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataRole;          

            $role = $this->roles->create($input);
			storeLogActivity('create', $user->id);
        	return response()->json(['message' => __('messages.create_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.create_error'), 'status' => 'error']);
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
        $role = $this->roles->formatRole()->find($id);

        if (!$role) {
            return response()->json(['message' => __('messages.not_found'),'status' => 'error'], 404); 
        }

        return response()->json(['message' => $role, 'status' => 'success'], 200);     
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

        $checkPermission = $user->verifyPermissionAuthorization('update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataRole = [
                'name' => $request->name,
                'description' => $request->description
            ];
		    $validated = $this->validator($dataRole);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataRole;  
            
            $role = $this->roles->find($id);

            if (!$role) {
	    		return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 404);
	    	}

            $role->fill($input);          
            $role->save();
			storeLogActivity('update', $user->id);
        	return response()->json(['message' => __('messages.update_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.update_error'), 'status' => 'error']);
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
        $checkPermission = $user->verifyPermissionAuthorization('delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $role = $this->roles->find($id);

            if (!$role) {
	    		return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 404);
            }
            
            $role->delete();
            storeLogActivity('delete', $user->id);
            return response()->json(['message' => __('messages.delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.delete_error'), 'status' => 'error']);
        }      
    }

    //Agregar permiso
    public function addPermission(Request $request, $id)
    {
        try {
            $role = $this->roles->find($id);
            
            if (!$role) {
                return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 422);
            }
            $submodule = Sub_module::where('id', '=', $request->submodule)->first();
            $range = Range_sub_module::where('id', '=', $request->range)->first();

            $permission_role = Permission_role::where('id', '=', $request->submodule)
                                                ->where('id', '=', $role->id)
                                                ->first();
            if (is_null($permission_role)) {
                $data_permission = [
                    'id' => $submodule->id,
                    'id' => $range->id,
                    'id' => $role->id
                ];
                $permission_role = Permission_role::create($data_permission);
                storeLogActivity('add_permission', $role->id);
                return response()->json(['message' => __('messages.create_permission_success'), 'status' => 'success'], 200);  
            } else {
                return response()->json(['message' => __('messages.create_permission_already_exists_error'), 'status' => 'error'], 400);  
            }

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.create_permission_error'), 'status' => 'error']);
        }

    }

    //Editar permiso
    public function editPermission(Request $request, $id)
    {
        try {
            $role = $this->roles->find($id);
            
            if (!$role) {
                return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 422);
            }
            $submodule = Sub_module::where('id', '=', $request->submodule)->first();
            $range = Range_sub_module::where('id', '=', $request->range)->first();

            $permission_role = Permission_role::where('id', '=', $request->submodule)
                                                ->where('id', '=', $role->id)
                                                ->first();

            if (!$permission_role) {
                $data_permission = [
                    'id' => $submodule->id,
                    'id' => $range->id,
                    'id' => $role->id
                ];
                $permission_role->fill($data_permission);
                $permission_role->save();
                storeLogActivity('edit_permission', $role->id);
                return response()->json(['message' => __('messages.update_permission_success'), 'status' => 'success'], 200);  
            } else {
                return response()->json(['message' => __('messages.update_permission_no_exist_error'), 'status' => 'error'], 400);  
            }           

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.update_permission_error'), 'status' => 'error']);
        }
    }

    //Eliminar permiso
    public function revokePermission(Request $request, $id)
    {
        try {
            $role = $this->roles->find($id);
            
            if (!$role) {
                return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 422);
            }

            $submodule = Sub_module::where('id', '=', $request->submodule)->first();

            $permission_role = Permission_role::where('id', '=', $request->submodule)
                                                ->where('id', '=', $role->id)
                                                ->first();

            if ($permission_role) {
                $permission_role->delete();
                storeLogActivity('revoke_permission', $role->id);
                return response()->json(['message' => __('messages.delete_permission_success'), 'status' => 'success'], 200);  
            } else {
                return response()->json(['message' => __('messages.delete_permission_no_exist_error'), 'status' => 'success'], 400);  
            }     
        } catch (Exception $e) {
            return response()->json(['message' => __('messages.delete_permission_error'), 'status' => 'error']);
        }        
    }
}
