<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\City;
use Travelestt\Models\Permission_role;
use Travelestt\Models\User;
use Travelestt\Models\Role;
use Travelestt\Models\Permission_user;
use Travelestt\Models\Sub_module;
use Travelestt\Models\Range_sub_module;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Travelestt\Http\Controllers\Controller;
use Travelestt\Http\Controllers\Api\APILogin;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;
use Travelestt\Http\Traits\ApiResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Users extends Controller
{
    use ApiResponse;

    public function __construct(User $users, APILogin $api_login)
    {
        $this->users = $users;
        $this->check_user = $api_login;
        $this->folder = 'assets/images/users/';

    }

    /**
     * Método para validar creación o actualización de un usuario
     *
     * @param \Illuminate\Http\Request  $request
     * @param integer $id
     * @return void
     */
    protected function validator(Request $request, $id = null)
    {
        $messages = [
            'firstname.required' => __('messages.firstname_required'),
            'firstname.string' => __('messages.firstname_string'),
            'lastname.required' => __('messages.lastname_required'),
            'lastname.string' => __('messages.lastname_string'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
            'phonenumber.string' => __('messages.phonenumber_string'),
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_email'),
            'email.unique' => __('messages.email_unique'),
            'password.required' => __('messages.password_required'),
            'password.min' => __('messages.password_min'),
            'password.regex' => __('messages.password_regex'),
            'password.confirmed' => __('messages.password_confirmed'),
            'status.required' => __('messages.status_required'),
            'id.required' => __('messages.id_required'),
            'id.exists' => __('messages.id_exists'),
        ];
    	$rules = [
            'firstname'      =>    'required|string',
            'lastname'       =>    'required|string',
            'id'             =>    'required|exists:city,id',
            'phonenumber'    =>    'nullable|string',
            'email'          =>    'required|email|unique:user',
            'password'       =>    'required|min:6|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*[A-Z])(?=.*\d)(?=.*([-+_!@#$%^&*.,;?])).+$/',
            'key'            =>    'nullable',
            'status'         =>    'required',
            'image'          =>    'nullable|file',
            'id'             =>    'required|exists:role,id'

        ];
        if ($id) {
            $rules['email'] = Rule::unique('user', 'email')->ignore($id, 'id');
            $rules['password'] = 'nullable|min:6|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*[A-Z])(?=.*\d)(?=.*([-+_!@#$%^&*.,;?])).+$/';
        }
        $this->validate($request, $rules, $messages);
    }

    /**
     * Método para desplegar listado de todos los usuarios
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $users = $this->users->formatUser()->get();

        if (!$users) {
            return $this->errorResponse(__('messages.users_not_found'), Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($users);
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
     * Método para almacenar un nuevo usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
		    $request->request->add(['status' => 1]); 
            if(!$request->id) {
                $request->request->add(['id' => 2]);
            }
            $this->validator($request);
            $request->request->add(['password' => Hash::make($request->password)]);
            $role = Role::find($request->id);
            $city = City::find($request->id);
            $user = $this->users->create($request->all());
            $user->role()->associate($role);
            $user->city()->associate($city);
            $this->assignRolesToNewUsers($user, $role);

            if (isset($request->file)) {
                $file = $request->file;
                $name = $file->getClientOriginalName();
                $file_name = 'user-'.$user->id.'.'.$file->getClientOriginalExtension();
                $url = $this->folder.$file_name;
                if (Storage::disk('local')->exists($url)) {
                    Storage::delete($url);
                }

                $image_resize = Image::make($file->getRealPath());              
                $image_resize->resize(250, 250);
                $image_resize->save($url);
                
                $user->image = $file_name;
                $user->save();   
            } 

            storeLogActivity('create', $user->id);
            return $this->successResponse(__('messages.create_success'), Response::HTTP_CREATED);
    	} catch (Exception $e) {
            return $this->errorResponse(__('messages.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
    	}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $user = $this->users->formatUser()->findOrFail($id);

         if (!$user) {
            return response()->json(['message' => __('messages.not_found'),'status' => 'error'], 404); 
        }

        return response()->json(['message' => $user, 'status' => 'success'], 200);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        
        $checkRole = $user->verifyRoleAuthorization($user->id);

        $checkPermission = $user->verifyPermissionAuthorization('update', ['self', 'all']);
        
        if (!$checkRole || !$checkPermission) {
            return $this->errorResponse(__('messages.no_permission'), Response::HTTP_UNAUTHORIZED);
        }
        try {
            $request->request->add(['status' => 1]); 
            $this->validator($request, $id);
            $user = $this->users->findOrFail($id);
            if ($request->password) {
	    		$request->request->add(['password' => Hash::make($request->password)]);
            }
            $user->fill($request->all()); 
            $role = Role::find($request->id);
            $city = City::find($request->id);
            $user->save();
            $user->role()->dissociate();
            $user->role()->associate($role);
            $user->city()->dissociate();
            $user->city()->associate($city);

            if (isset($request->file)) {
                $file = $request->file;
                $name = $file->getClientOriginalName();
                $file_name = 'user-'.$user->id.'.'.$file->getClientOriginalExtension();
                $url = $this->folder.$file_name;
                if (Storage::disk('local')->exists($url)) {
                    Storage::delete($url);
                }

                $image_resize = Image::make($file->getRealPath());              
                $image_resize->resize(250, 250);
                $image_resize->save($url);
                
                $user->image = $file_name;
                $user->save();   
            } 

            storeLogActivity('update', $user->id);
            return $this->successResponse(__('messages.update_success'), Response::HTTP_CREATED);  
    	} catch (Exception $e) {
            return $this->errorResponse(__('messages.update_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $user = $this->users->find($id);

            if (!$user) {
                return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 404);
            }

            $user->delete();
            storeLogActivity('delete', $user->id);
            return response()->json(['message' => __('messages.delete_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.delete_error'), 'status' => 'error']);
        }      
    }

    /**
     * Change the status of the user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id)
    {
        $user = $this->check_user->getUserAuthenticated($request->token);
        
        $checkRole = $user->verifyRoleAuthorization($user->id);
        $checkPermission = $user->verifyPermissionAuthorization('update', ['self', 'all']);

        if (!$checkRole || !$checkPermission) {
            return response()->json(['message' => __('messages.no_permission'), 'status' => 'error'], 401);
        }

        try {
            $user = $this->users->find($id);
            if ($user->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();
            storeLogActivity('update_status', $user->id);
            return response()->json(['message' => __('messages.update_success'), 'status' => 'success'], 200);     

        } catch (Exception $e) {
            return response()->json(['message' => __('messages.update_error'), 'status' => 'error']);
        }      
    }

    //Agregar permiso
    public function addPermission(Request $request, $id)
    {
        try {
            $user = $this->users->find($id);
            
            if (!$user) {
                return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 422);
            }
            $submodule = Sub_module::where('id', '=', $request->submodule)->first();
            $range = Range_sub_module::where('id', '=', $request->range)->first();

            $permission_user = Permission_user::where('id', '=', $request->submodule)
                                                ->where('id', '=', $user->id)
                                                ->first();
            if (is_null($permission_user)) {
                $data_permission = [
                    'id' => $submodule->id,
                    'id' => $range->id,
                    'id' => $user->id
                ];
                $permission_user = Permission_user::create($data_permission);
                storeLogActivity('add_permission', $user->id);
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
            $user = $this->users->find($id);
            
            if (!$user) {
                return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 422);
            }
            $submodule = Sub_module::where('id', '=', $request->submodule)->first();
            $range = Range_sub_module::where('id', '=', $request->range)->first();

            $permission_user = Permission_user::where('id', '=', $request->submodule)
                                                ->where('id', '=', $user->id)
                                                ->first();

            if (count($permission_user) > 0) {
                $data_permission = [
                    'id' => $submodule->id,
                    'id' => $range->id,
                    'id' => $user->id
                ];
                $permission_user->fill($data_permission);
                $permission_user->save();
                storeLogActivity('edit_permission', $user->id);
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
            $user = $this->users->find($id);
            
            if (!$user) {
                return response()->json(['message' => __('messages.not_found'), 'status' => 'error'], 422);
            }

            $submodule = Sub_module::where('id', '=', $request->submodule)->first();

            $permission_user = Permission_user::where('id', '=', $request->submodule)
                                                ->where('id', '=', $user->id)
                                                ->first();

            if ($permission_user) {
                $permission_user->delete();
                storeLogActivity('revoke_permission', $user->id);
                return response()->json(['message' => __('messages.delete_permission_success'), 'status' => 'success'], 200);  
            } else {
                return response()->json(['message' => __('messages.delete_permission_no_exist_error'), 'status' => 'success'], 400);  
            }     
        } catch (Exception $e) {
            return response()->json(['message' => __('messages.delete_permission_error'), 'status' => 'error']);
        }        
    }

    //Asignar roles a los nuevos usuarios
    public function assignRolesToNewUsers($user, $role)
    {
        $submodules = Sub_module::all()->toArray();
        $permissionsRole = Permission_role::where('id', '=', $role->id)->get()->toArray();
        if (!empty($permissionsRole)) {
            foreach ($submodules as $submodule) {
                foreach($permissionsRole as $permissionRole) {
                    if ($submodule['id'] == $permissionRole['id']) {
                        $data = [
                            'id' => $submodule['id'],
                            'id' => $permissionRole['id'],
                            'id' => $user['id']
                        ];
                        Permission_user::create($data);
                    }
                }
            }
        } else {
            $range = Range_sub_module::where('name', '=', 'none')->first()->toArray();
            foreach ($submodules as $submodule) {
                if (strpos($submodule['name'], '_read') !== false) { 
                    $data = [
                        'id' => $submodule['id'],
                        'id' => $range['id'],
                        'id' => $user['id']
                    ];
                    Permission_user::create($data);
                }
            }
        }
    }

    /**
     * Método para generar llave de recuperacion de contraseña
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function generateKeyForRecoveryPassword(Request $request)
    {
        $messages = [
            'email.required'   =>    __('messages.email_required'),
            'email.email'      =>    __('messages.email_email'),
            'email.exists'     =>    __('messages.email_exists'),
        ];
    	$rules = [
            'email'            =>    'required|email|exists:user,email'
        ];
        $this->validate($request, $rules, $messages);
        $user = User::where('email', '=', $request->email)->first();
        $key = bcrypt(date('Y-m-d').$user->id);
        $user->key = $key;
        $user->save();

        $title = __('messages.send_recovery_password');
        $sender = 'support@travelestt.com';
        $receiver = $request->email;
        $message = url(app()->getLocale()."/".__('routes.new_password')).'?key='.$user->key;
        $footer = '';
        $result = sendEmail($sender, $receiver, $message, $title, $footer);
        if ($result == 'email_success') {
            storeLogActivity('recovery_password_request', $user->id);
            return $this->successResponse(__('message.key_success'));
        } else {
            return response()->json(['message' => __('messages.email_error'), 'status' => 'error']);
        }
        
    }

    /**
     * Método para asignar nueva contraseña a un usuario
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function generateNewPassword(Request $request)
    {
        $messages = [
            'password.required'     =>    __('messages.password_required'),
            'password.min'          =>    __('messages.password_min'),
            'password.regex'        =>    __('messages.password_regex'),
            'password.confirmed'    =>    __('messages.password_confirmed'),
        ];
    	$rules = [
            'password'    =>    'required|min:6|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*[A-Z])(?=.*\d)(?=.*([-+_!@#$%^&*.,;?])).+$/',
        ];
        $this->validate($request, $rules, $messages);
        $user = User::where('key', '=', $request->key)->first();
        $user->password = Hash::make($request->password);
        $user->key = null;
        $user->save();
        storeLogActivity('change_password', $user->id);
        return $this->successResponse( [ 'email' => $user->email ] );
    }
}