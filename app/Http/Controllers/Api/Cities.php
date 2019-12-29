<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\City;
use Travelestt\Models\Province;
use Travelestt\Models\Country;
use Travelestt\Models\Coast;
use Travelestt\Models\Festival;
use Travelestt\Models\Museum;
use Travelestt\Models\Night_spot;
use Travelestt\Models\Point_of_interest;
use Travelestt\Models\Route;
use Travelestt\Models\Street_market;
use Travelestt\Models\Show;
use Travelestt\Models\Natural_space;
use Travelestt\Models\Experience;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Cities extends Controller
{
    public function __construct(City $cities, APILogin $api_login, Coast $coast, Festival $festival, Museum $museum, Night_spot $night_spot, Point_of_interest $point_of_interest, Route $route, Street_market $street_market, Show $show, Natural_space $natural_space, Experience $experience)
    {
        $this->cities = $cities;
        $this->coast = $coast;
        $this->festival = $festival;
        $this->museum = $museum;
        $this->night_spot = $night_spot;
        $this->point_of_interest = $point_of_interest;
        $this->route = $route;
        $this->street_market = $street_market;
        $this->show = $show;
        $this->natural_space = $natural_space;
        $this->experience = $experience;
        $this->check_user = $api_login;
        $this->folder = 'assets/images/cities/';
    }

    protected function validator(array $data, $id = null)
    {
        $messages = [
            'name.required' => __('messages.name_required'),
            'name.string' => __('messages.name_string'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
            'slug.required' => __('messages.slug_required'),
            'slug.unique' => __('messages.slug_unique'),
            'latitude.requerid' => 'latitud es requewrida',
            'longitude.requerid' => 'longitud es requewrida',
            'altitude.requerid' => 'altitude es requewrida',
            'latitude.between' => 'latitud fuera d erango',
            'longitude.between' => 'longitud fuera d erango',
        ];
    	$rules = [
            'name' => 'required|string',
            'id' => 'required|exists:country,id',
            'id' => 'required|exists:province,id',
            'latitude' => 'required|between:-180.000000,180.000000',
            'longitude' => 'required|between:-360.000000,360.000000',
            'altitude' => 'required|min:0',
            'slug' => 'required|unique:city'
        ];

        if ($id) {
            $rules['slug'] = Rule::unique('city', 'slug')->ignore($id, 'id');
        }

    	return Validator::make($data, $rules, $messages);
    }

    protected function parseErrors($validated_errors)
    {
        $errors = [];
        foreach ($validated_errors->all() as $error) {
            foreach ($validated_errors->get('name') as $message) {
                $errors['name'] = $message;
            }
            foreach ($validated_errors->get('slug') as $message) {
                $errors['slug'] = $message;
            }
            foreach ($validated_errors->get('id') as $message) {
                $errors['country'] = $message;
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
        $cities = $this->cities->formatCity()->get();

        if ($cities == null) {
            return response()->json(['message' => __('messages.cities_not_found'), 'status' => 'error'], 404); 
        }
        
        return response()->json(['message' => $cities, 'status' => 'success'], 200);     
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

        $checkPermission = $user->verifyPermissionAuthorization('create', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataCity = [
                'name'         =>    $request->name,
                'slug'         =>    $request->slug,
                'latitude'     =>    $request->latitude,
                'longitude'    =>    $request->longitude,
                'altitude'     =>    ($request->altitude) ? $request->altitude : 0,
                'id'           =>    $request->country,
                'id'           =>    $request->province
            ];
		    $validated = $this->validator($dataCity);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataCity; 
            
            $country = Country::find($request->country);
            $province = Province::find($request->province);
            $city = $this->cities->create($input);
            $city->country()->associate($country);
            $city->province()->associate($province);

            if (isset($request->file)) {
                $file = $request->file;
                $name = $file->getClientOriginalName();
                $file_name = 'city-'.$city->id.'.'.$file->getClientOriginalExtension();
                $url = $this->folder.$file_name;
                if (Storage::disk('local')->exists($url)) {
                    Storage::delete($url);
                }

                $image_resize = Image::make($file->getRealPath());              
                $image_resize->resize(800, 600);
                $image_resize->save($url);
                
                $city->image = $file_name;
                $city->save();   
            } 
			storeLogActivity('create', $city->id);
        	return response()->json(['message' => __('messages.create_success'), 'status'=> 'success'], 201);     

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
    public function show(Request $request, $id)
    {            
        $city = $this->cities->formatCity()->find($id);

        if ($city == null) {
            return response()->json(['message' => __('messages.not_found') , 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $city, 'status' => 'success'], 200);     
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
        
        $checkPermission = $user->verifyPermissionAuthorization('update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
		    $dataCity = [
                'name'         =>    $request->name,
                'slug'         =>    $request->slug,
                'latitude'     =>    $request->latitude,
                'longitude'    =>    $request->longitude,
                'altitude'     =>    ($request->altitude) ? $request->altitude : 0,
                'id'           =>    $request->country,
                'id'           =>    $request->province
            ];
		    $validated = $this->validator($dataCity, $id);

	    	if ($validated->fails()) {
                $errors = $this->parseErrors($validated->errors());
	    		return response()->json(['message' => $errors, 'status' => 'error'], 400);
	    	}

			$input = $dataCity; 
	    	$city = $this->cities->find($id);

            if ($city == null) {
	    		return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 404);
	    	}

            $city->fill($input);  
            $country = Country::find($request->country);
            $province = Province::find($request->province);
            $city->save();
            $city->country()->dissociate();
            $city->province()->dissociate();
            $city->country()->associate($country);
            $city->province()->associate($province);
            if (isset($request->file)) {
                $file = $request->file;
                $name = $file->getClientOriginalName();
                $file_name = 'city-'.$city->id.'.'.$file->getClientOriginalExtension();
                $url = $this->folder.$file_name;
                if (Storage::disk('local')->exists($url)) {
                    Storage::delete($url);
                }

                $image_resize = Image::make($file->getRealPath());              
                $image_resize->resize(800, 600);
                $image_resize->save($url);
                
                $city->image = $file_name;
                $city->save();   
            } 
			storeLogActivity('update', $city->id);
        	return response()->json(['message' => __('messages.update_success'), 'status' => 'success'], 201);     

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
        
        $checkRole = $user->verifyRoleAuthorization($user->id);;
        $checkPermission = $user->verifyPermissionAuthorization('delete', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $city = $this->cities->find($id);

            if ($city == null) {
	    		return response()->json(['message' => __('messages.cities_not_found'), 'status' => 'error'], 404);
            }

            $city->delete();
            storeLogActivity('delete', $user->id);
            return response()->json(['message' => __('messages.delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.delete_error'), 'status' => 'error']);
        }      
    }

    /**
     * Change the outstanding of the city
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeOutstanding(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);

        $checkRole = $user->verifyRoleAuthorization($user->id);;
        $checkPermission = $user->verifyPermissionAuthorization('update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $city = $this->cities->find($id);

            if ($city == NULL) {
	    		return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 404);
            }

            if ($city->outstanding == 0) {
                $city->outstanding = 1;
            } else if ($city->outstanding == 1) {
                $city->outstanding = 0;
            }
            $city->save();
            storeLogActivity('update_outstanding', $user->id);
            return response()->json(['message' => __('messages.update_success'), 'status' => 'success'], 200);

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.update_error'), 'status' => 'error']);
        }
    }

    /**
     * Change the outstanding of the city
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeTopDestination(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);

        $checkRole = $user->verifyRoleAuthorization($user->id);;
        $checkPermission = $user->verifyPermissionAuthorization('update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $city = $this->cities->find($id);

            if ($city == NULL) {
	    		return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 404);
            }

            if ($city->top_destination == 0) {
                $city->top_destination = 1;
            } else if ($city->top_destination == 1) {
                $city->top_destination = 0;
            }
            $city->save();
            storeLogActivity('update_top_destination', $user->id);
            return response()->json(['message' => __('messages.update_success'), 'status' => 'success'], 200);

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.update_error'), 'status' => 'error']);
        }
    }

    public function getCitiesByCountry(Request $request, $country)
    {
        $cities = $this->cities->formatCityByCountry()->byCountry($country)->get();

        if (count($cities) == 0) {
            return response()->json(['message' => __('messages.cities_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $cities, 'status' => 'success'], 200); 
    }

    public function getCitiesByProvince(Request $request, $province)
    {
        $cities = $this->cities->formatCityByProvince()->byProvince($province)->get();

        if (count($cities) == 0) {
            return response()->json(['message' => __('messages.cities_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $cities, 'status' => 'success'], 200); 
    }

    public function getCityBySlug(Request $request, $slug)
    {
        $city = $this->cities->formatCity()->bySlug($slug)->get()->toArray();

        if (count($city) == 0) {
            return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $city, 'status' => 'success'], 200); 
    }

    public function getResources(Request $request, $city)
    {
        $resources = [];

        $coasts = $this->coast->formatCoast()->byCity($city)->get();
        if (count($coasts) > 0) {
            $coasts = $this->coast->parseCoastToFront($coasts)->toArray();
            for ($i = 0; $i < count($coasts); $i++) {
                foreach ($coasts[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $coasts[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $coasts[$i]['translations'][$a][$c] = str_limit($coasts[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['coasts'] = $coasts;
        }

        $festivals = $this->festival->formatFestival()->byCity($city)->get();
        if (count($festivals) > 0) {
            $festivals = $this->festival->parseFestivalToFront($festivals)->toArray();
            for ($i = 0; $i < count($festivals); $i++) {
                foreach ($festivals[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $festivals[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $festivals[$i]['translations'][$a][$c] = str_limit($festivals[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['festivals'] = $festivals;
        }

        $shows = $this->show->formatShow()->byCity($city)->get();
        if (count($shows) > 0) {
            $shows = $this->show->parseShowToFront($shows)->toArray();
            for ($i = 0; $i < count($shows); $i++) {
                foreach ($shows[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $shows[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $shows[$i]['translations'][$a][$c] = str_limit($shows[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            if ($resources['festivals']) {
                $resources['festivals'] = array_merge($resources['festivals'], $shows);
            } else {
                $resources['festivals'] = $shows;
            }
        }

        $museums = $this->museum->formatMuseum()->byCity($city)->get();
        if (count($museums) > 0) {
            $museums = $this->museum->parseMuseumToFront($museums)->toArray();
            for ($i = 0; $i < count($museums); $i++) {
                foreach ($museums[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $museums[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $museums[$i]['translations'][$a][$c] = str_limit($museums[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['museums'] = $museums;
        }

        $night_spots = $this->night_spot->formatNightSpot()->byCity($city)->get();
        if (count($night_spots) > 0) {
            $night_spots = $this->night_spot->parseNightSpotToFront($night_spots)->toArray();
            for ($i = 0; $i < count($night_spots); $i++) {
                foreach ($night_spots[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $night_spots[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $night_spots[$i]['translations'][$a][$c] = str_limit($night_spots[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['night_spots'] = $night_spots;
        }

        $points_of_interest = $this->point_of_interest->formatPointOfInterest()->byCity($city)->get();
        if (count($points_of_interest) > 0) {
            $points_of_interest = $this->point_of_interest->parsePointOfInterestToFront($points_of_interest)->toArray();
            for ($i = 0; $i < count($points_of_interest); $i++) {
                foreach ($points_of_interest[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $points_of_interest[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $points_of_interest[$i]['translations'][$a][$c] = str_limit($points_of_interest[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['points_of_interest'] = $points_of_interest;
        }

        $natural_spaces = $this->natural_space->formatNaturalSpace()->byCity($city)->get();
        if (count($natural_spaces) > 0) {
            $natural_spaces = $this->natural_space->parseNaturalSpaceToFront($natural_spaces)->toArray();
            for ($i = 0; $i < count($natural_spaces); $i++) {
                foreach ($natural_spaces[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $natural_spaces[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $natural_spaces[$i]['translations'][$a][$c] = str_limit($natural_spaces[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['natural_spaces'] = $natural_spaces;
        }

        $routes_hiker = $this->route->byCharacteristics(['hiker'])->formatRoute()->byCity($city)->get();
        if (count($routes_hiker) > 0) {
            $routes_hiker = $this->route->parseRouteToFront($routes_hiker)->toArray();
            for ($i = 0; $i < count($routes_hiker); $i++) {
                $routes_hiker[$i]['type'] = 'route';
                foreach ($routes_hiker[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $routes_hiker[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $routes_hiker[$i]['translations'][$a][$c] = str_limit($routes_hiker[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['routes_hiker'] = $routes_hiker;
        }

        $routes_btt = $this->route->byCharacteristics(['btt'])->formatRoute()->byCity($city)->get();
        if (count($routes_btt) > 0) {
            $routes_btt = $this->route->parseRouteToFront($routes_btt)->toArray();
            for ($i = 0; $i < count($routes_btt); $i++) {
                $routes_btt[$i]['type'] = 'route';
                foreach ($routes_btt[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $routes_btt[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $routes_btt[$i]['translations'][$a][$c] = str_limit($routes_btt[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['routes_btt'] = $routes_btt;
        }

        $routes_highway = $this->route->byCharacteristics(['highway'])->formatRoute()->byCity($city)->get();
        if (count($routes_highway) > 0) {
            $routes_highway = $this->route->parseRouteToFront($routes_highway)->toArray();
            for ($i = 0; $i < count($routes_highway); $i++) {
                $routes_highway[$i]['type'] = 'route';
                foreach ($routes_highway[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $routes_highway[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $routes_highway[$i]['translations'][$a][$c] = str_limit($routes_highway[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['routes_highway'] = $routes_highway;
        }

        $street_markets = $this->street_market->formatStreetMarket()->byCity($city)->get();
        if (count($street_markets) > 0) {
            $street_markets = $this->street_market->parseStreetMarketToFront($street_markets)->toArray();
            for ($i = 0; $i < count($street_markets); $i++) {
                foreach ($street_markets[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $street_markets[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $street_markets[$i]['translations'][$a][$c] = str_limit($street_markets[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['street_markets'] = $street_markets;
        }

        $experiences = $this->experience->formatExperience()->byCity($city)->get();
        if (count($experiences) > 0) {
            $experiences = $this->experience->parseExperienceToFront($experiences)->toArray();
            for ($i = 0; $i < count($experiences); $i++) {
                foreach ($experiences[$i]['translations'] as $a => $b) {
                    foreach ($b as $c => $d) {
                        $experiences[$i]['translations'][$a][$c] = strip_tags($d);
                        if ($c == 'short_description') {
                            $experiences[$i]['translations'][$a][$c] = str_limit($experiences[$i]['translations'][$a][$c], 200);
                        }
                    }
                }
            }
            $resources['experiences'] = $experiences;
        }

        if (empty($resources)) {
            return response()->json(['message' => __('messages.resources_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $resources, 'status' => 'success'], 200); 
    }

    public function getCitiesFeatured(Request $request)
    {
        $city = $this->cities->formatCity()->byFeatured()->get()->toArray();

        if (count($city) == 0) {
            return response()->json(['message' => __('messages.cities_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $city, 'status' => 'success'], 200); 
    }

    public function getCitiesTopDestinations(Request $request)
    {
        $city = $this->cities->formatCity()->byTopDestinations()->get()->toArray();

        if (count($city) == 0) {
            return response()->json(['message' => __('messages.cities_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $city, 'status' => 'success'], 200); 
    }
}
