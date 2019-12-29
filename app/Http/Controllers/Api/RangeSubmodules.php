<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Travelestt\Models\Range_sub_module;
use Illuminate\Support\Facades\Validator;

class RangeSubmodules extends Controller
{
    public function __construct(Range_sub_module $ranges, APILogin $api_login)
    {
        $this->ranges = $ranges;
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
    public function index(Request $request)
    {
        if ($request->header('Accept-Language') != null) {
            if (in_array($request->header('Accept-Language'), \Config::get('app.available_language'))) {
                app()->setLocale($request->header('Accept-Language'));
            }
        }

        $ranges = $this->ranges->formatRangeSubModule()->get();

        if (count($ranges) == 0) {
            return response()->json(['message' => __('messages.ranges_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $ranges, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('range_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataRange = [
                'name' => $request->name,
                'description' => $request->description
            ];
		    $validated = $this->validator($dataRange);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataRange;            

            $range = $this->ranges->create($input);
			storeLogActivity('range_create', $user->id);
        	return response()->json(['message' => __('messages.range_create_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.range_create_error'), 'status' => 'error']);
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

        $range = $this->ranges->formatRangeSubModule()->find($id);

        if (!$range) {
            return response()->json(['message' => __('messages.range_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $range, 'status' => 'success'], 200);     
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
        $checkPermission = $user->verifyPermissionAuthorization('range_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataRange = [
                'name' => $request->name,
                'description' => $request->description
            ];
		    $validated = $this->validator($dataRange);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataRange;
	    	$range = $this->ranges->find($id);

            if (!$range) {
	    		return response()->json(['message' => __('messages.range_not_found'), 'status' => 'error'], 404);
	    	}

            $range->fill($input);          
            $range->save();
			storeLogActivity('range_update', $user->id);
        	return response()->json(['message' => __('messages.range_update_success'), 'status' => 'success'], 200);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.range_update_error'), 'status' => 'error']);
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
        $checkPermission = $user->verifyPermissionAuthorization('range_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $range = $this->ranges->find($id);

            if (!$range) {
	    		return response()->json(['message' => __('messages.range_not_found'), 'status' => 'error'], 404);
            }

            $range->delete();
            storeLogActivity('range_delete', $user->id);
            return response()->json(['message' => __('messages.range_delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.range_delete_error'), 'status' => 'error']);
        }      
    }
}
