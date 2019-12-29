<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\Province;
use Travelestt\Models\Country;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Provinces extends Controller
{
    public function __construct(Province $provinces, APILogin $api_login)
    {
        $this->provinces = $provinces;
        $this->check_user = $api_login;
    }

    protected function validator(array $data)
    {
        $messages = [
            'name.required' => __('messages.name_required'),
            'name.string' => __('messages.name_string'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists')
        ];
    	$rules = [
            'name' => 'required|string',
            'id' => 'required|exists:country,id'
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
                $errors['country'] = $message;
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
        $provinces = $this->provinces->formatProvince()->get();

        if (count($provinces) == 0) {
            return response()->json(['message' => __('messages.provinces_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $provinces, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('province_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataCountry = [
                'name'    =>    $request->name,
                'id'      =>    $request->country
            ];
		    $validated = $this->validator($dataCountry);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataCountry;  

            $country = Country::find($request->country);
            $province = $this->provinces->create($input);
            $province->country()->associate($country);
            $province->save();
			storeLogActivity('province_create', $user->id);
        	return response()->json(['message' => __('messages.province_create_success'), 'status'=> 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.province_create_error'), 'status' => 'error']);
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
        $province = $this->provinces->formatProvince()->find($id);

        if ($province == null) {
            return response()->json(['message' => __('messages.province_not_found'),'status' => 'error'], 404); 
        }

        return response()->json(['message' => $province, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('province_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataCountry = [
                'name'    =>    $request->name,
                'id'      =>    $request->country
            ];
		    $validated = $this->validator($dataCountry);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataCountry;
	    	$province = $this->provinces->find($id);

            if ($province == null) {
	    		return response()->json(['message' => __('messages.province_not_found'), 'status' => 'error'], 404);
	    	}

            $province->fill($input);          
            
            $country = Country::find($request->country);
            $province->country()->dissociate();
            $province->country()->associate($country);
            $province->save();
			storeLogActivity('province_update', $user->id);
        	return response()->json(['message' => __('messages.province_update_success'), 'status' => 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.province_update_error'), 'status' => 'error']);
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
        $checkPermission = $user->verifyPermissionAuthorization('province_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $province = $this->provinces->find($id);

            if ($province == null) {
	    		return response()->json(['message' => __('messages.province_not_found'), 'status' => 'error'], 404);
            }

            $province->delete();
            storeLogActivity('province_delete', $user->id);
            return response()->json(['message' => __('messages.province_delete_success'), 'status' => 'success'], 204);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.province_delete_error'), 'status' => 'error']);
        }      
    }

    public function getProvincesByCountry(Request $request, $id)
    {
        $provinces = $this->provinces->formatProvince()->byCountry($id)->get();

        if (count($provinces) == 0) {
            return response()->json(['message' => __('messages.provinces_not_found'),'status' => 'error'], 404); 
        }

        return response()->json(['message' => $provinces, 'status' => 'success'], 200); 
    }
}
