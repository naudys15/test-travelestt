<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\Module;
use Travelestt\Models\Sub_module;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Support\Facades\Validator;

class Submodules extends Controller
{
    public function __construct(Sub_module $submodules, APILogin $api_login)
    {
        $this->submodules = $submodules;
        $this->check_user = $api_login;
    }

    protected function validator(array $data)
    {
        $messages = [
            'name.required' => __('messages.name_required'),
            'name.string' =>  __('messages.name_string'),
            'description.required' =>  __('messages.description_required'),
            'description.string' => __('messages.description_string'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
        ];
    	$rules = [
            'name' => 'required|string',
            'description' => 'required|string',
            'id' => 'required|exists:module,id',
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
                $errors['module'] = $message;
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
        if ($request->header('Accept-Language') != null) {
            if (in_array($request->header('Accept-Language'), \Config::get('app.available_language'))) {
                app()->setLocale($request->header('Accept-Language'));
            }
        }

        $submodules = $this->submodules->formatSubModule()->get();

        if (!$submodules) {
            return response()->json(['message' => __('messages.submodules_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $submodules, 'status' => 'success'], 200);     
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

        $checkPermission = $user->verifyPermissionAuthorization('submodule_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        if ($request->header('Accept-Language') != null) {
            if (in_array($request->header('Accept-Language'), Config::get('app.available_language'))) {
                app()->setLocale($request->header('Accept-Language'));
            }
        }

        try {
		    $dataSubmodule = [
                'name' => $request->name,
                'description' => $request->description,
                'id' => $request->module
            ];
		    $validated = $this->validator($dataSubmodule);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataSubmodule; 

            $module = Module::find($request->module);
            $submodule = $this->submodules->create($input);
            $submodule->module()->associate($module);
            $submodule->save();
			storeLogActivity('submodule_create', $user->id);
        	return response()->json(['message' => __('messages.submodule_create_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.submodule_create_error'), 'status' => 'error']);
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
        if ($request->header('Accept-Language') != null) {
            if (in_array($request->header('Accept-Language'), \Config::get('app.available_language'))) {
                app()->setLocale($request->header('Accept-Language'));
            }
        }

        $submodule = $this->submodules->formatSubModule()->find($id);

        if (!$submodule) {
            return response()->json(['message' => __('messages.submodule_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $submodule, 'status' => 'success'], 200);     
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
        $checkPermission = $user->verifyPermissionAuthorization('submodule_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataSubmodule = [
                'name' => $request->name,
                'description' => $request->description,
                'id' => $request->module
            ];
		    $validated = $this->validator($dataSubmodule);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataSubmodule; 
	    	$submodule = $this->submodules->find($id);

            if (!$submodule) {
	    		return response()->json(['message' => __('messages.submodule_not_found'), 'status' => 'error'], 404);
            }
            
            $submodule->fill($input); 
              
            $module = Module::find($request->module);
            $submodule->module()->dissociate();
            $submodule->module()->associate($module);
            $submodule->save();
			storeLogActivity('submodule_update', $user->id);
        	return response()->json(['message' => __('messages.submodule_update_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.submodule_update_error'), 'status' => 'error']);
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
        $checkPermission = $user->verifyPermissionAuthorization('submodule_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $submodule = $this->submodules->find($id);

            if (!$submodule) {
	    		return response()->json(['message' => __('messages.submodule_not_found'), 'status' => 'error'], 404);
            }

            $submodule->delete();
            storeLogActivity('submodule_delete', $user->id);
            return response()->json(['message' => __('messages.submodule_delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.submodule_delete_error'), 'status' => 'error']);
        }      
    }
}
