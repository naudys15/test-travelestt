<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\Country;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Countries extends Controller
{
    public function __construct(Country $countries, APILogin $api_login)
    {
        $this->countries = $countries;
        $this->check_user = $api_login;
    }

    protected function validator(array $data, $id = null)
    {
        $messages = [
            'iso.required' => __('messages.iso_required'),
            'iso.string' => __('messages.iso_string'),
            'iso.unique' => __('messages.iso_unique'),
            'name.required' => __('messages.name_required'),
            'name.string' => __('messages.name_string')
        ];
    	$rules = [
        	'iso' => 'required|string|unique:country,iso',
        	'name' => 'required|string'
        ];
        if ($id) {
            $rules['iso'] = Rule::unique('country', 'iso')->ignore($id, 'id');
        }

    	return Validator::make($data, $rules, $messages);
    }

    protected function parseErrors($validated_errors)
    {
        $errors = [];
        foreach ($validated_errors->all() as $error) {
            foreach ($validated_errors->get('iso') as $message) {
                $errors['iso'] = $message;
            }
            foreach ($validated_errors->get('name') as $message) {
                $errors['name'] = $message;
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
        $countries = $this->countries->formatCountry()->get();

        if (count($countries) == 0) {
            return response()->json(['message' => __('messages.countries_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $countries, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('country_create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataCountry = [
                'iso'     =>    strtoupper($request->iso),
                'name'    =>    $request->name
            ];
		    $validated = $this->validator($dataCountry);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataCountry;        

            $country = $this->countries->create($input);
			storeLogActivity('country_create', $user->id);
        	return response()->json(['message' => __('messages.country_create_success'), 'status'=> 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.country_create_error'), 'status' => 'error']);
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
        $country = $this->countries->formatCountry()->find($id);

        if ($country == null) {
            return response()->json(['message' => __('messages.country_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $country, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('country_update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataCountry = [
                'iso'     =>    strtoupper($request->iso),
                'name'    =>    $request->name
            ];
		    $validated = $this->validator($dataCountry, $id);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataCountry;
	    	$country = $this->countries->find($id);

            if ($country == null) {
	    		return response()->json(['message' => __('messages.country_not_found'), 'status' => 'error'], 404);
	    	}

            $country->fill($input);          
            $country->save();
			storeLogActivity('country_update', $user->id);
        	return response()->json(['message' => __('messages.country_update_success'), 'status' => 'success'], 201);     

    	} catch (Exception $e) {
            return response()->json(['message' => __('messages.country_update_error'), 'status' => 'error']);
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
        $checkPermission = $user->verifyPermissionAuthorization('country_delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $country = $this->countries->find($id);

            if ($country == null) {
	    		return response()->json(['message' => __('messages.country_not_found'), 'status' => 'error'], 404);
            }

            $country->delete();
            storeLogActivity('country_delete', $user->id);
            return response()->json(['message' => __('messages.country_delete_success'), 'status' => 'success'], 204);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.country_delete_error'), 'status' => 'error']);
        }      
    }
}
