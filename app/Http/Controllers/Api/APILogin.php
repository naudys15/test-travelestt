<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\User;
use Travelestt\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Session;
use Carbon\Carbon;

class APILogin extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    protected function validator(array $data)
    {
        $messages = [
            'email.required'    =>    __('messages.email_required'),
            'email.email'       =>    __('messages.email_email'),
            'password.required'      =>    __('messages.password_required'),
            'password.min'           =>    __('messages.password_min'),
            'role.required'          =>    __('messages.required'),
            'role.exists'            =>    __('messages.exists'),
        ];
    	$rules = [
            'email'    =>    'required|email',
            'password'      =>    'required|min:6',
            'role'          =>    'required|exists:role,name',
        ];

    	return Validator::make($data, $rules, $messages);
    }

    protected function parseErrors($validated_errors)
    {
        $errors = [];
        foreach ($validated_errors->all() as $error) {
            foreach ($validated_errors->get('email') as $message) {
                $errors['email'] = $message;
            }
            foreach ($validated_errors->get('password') as $message) {
                $errors['password'] = $message;
            }
            foreach ($validated_errors->get('role') as $message) {
                $errors['role'] = $message;
            }
        }
        return $errors;
    }

    public function login(Request $request) 
    {
        $dataLogin = [
            'email'    =>    $request->email,
            'password'      =>    $request->password,
            'role'          =>    $request->role
        ];
        $validated = $this->validator($dataLogin);
        unset($dataLogin['role']);
        if ($validated->fails()) {
            $errors = $this->parseErrors($validated->errors());
            return response()->json(['message' => $errors, 'status' => 'error'], 400);
        }
        $credentials = $dataLogin;
        if (!$token = auth('api')->attempt($credentials)) {
            $check = $this->user->checkCredentials($credentials);
            if ($check == 'wrong_password') {
                return response()->json(['message' => ['password' => __('messages.login_wrong_password_error')], 'status' => 'error'], 400);
            } elseif ($check == 'wrong_email') {
                return response()->json(['message' => ['email' => __('messages.login_wrong_email_error')], 'status' => 'error'], 400);
            }
        } 
        
        Session::put('authenticated', $request->email);
        $user = $this->user->where('email', '=', $request->email)->with('permission')->with('role')->first()->toArray();
        $permissions = [];
        if (!empty($user)) {
            if ($user['role']['name'] != $request->role) {
                return response()->json(['message' => __('messages.login_refused'), 'status' => 'error'], 400);
            }
            foreach ($user['permission'] as $permission) {
                $data = [
                    'submodule' => $permission['id'],
                    'range'  => $permission['id']
                ];
                $permissions[] = $data;
            }
            Session::put('permissions', serialize($permissions));
            storeLogActivity('login', $user['id']);
        }

        $data = [];
        $data['email'] = $user['email'];
        $data['full_name'] = $user['full_name'];
        $data['image'] = $user['image'];

        return response()->json([
            'message' => [
                'token' => $token,
                'user' => $data,
                'expires' => Carbon::now()->addMinute(auth('api')->factory()->getTTL())->toDateTimeString(),
            ],
            'status' => 'success'
        ]);
    }

    public function renewToken(Request $request) 
    {
        $new_token = JWTAuth::refresh($request->old_token);
        return response()->json([
            'message' => [
                'token' => $new_token,
                'expires' => auth('api')->factory()->getTTL() * 60,
            ],
            'status' => 'success'
        ]);
    }

    public function logout(Request $request) 
    {
		$this->validate($request, [
			'token' => 'required'
		]);

		try {
            $user = JWTAuth::authenticate($request->token);
            JWTAuth::invalidate($request->token);
            Session::forget('authenticated');
            Session::forget('permissions');
            storeLogActivity('logout', $user->id);
			return  response()->json([
                'message' => __('messages.logout_success'),
                'status' => 'success',
            ], 200);
		} catch (JWTException $exception) {
			return  response()->json([
                'message' => __('messages.logout_error'),
                'status' => 'error'
			], 500);
		}
    }
    public function getAuthUser(Request $request) 
    {
		/*$this->validate($request, [
			'token' => 'required'
		]);*/
        $user = JWTAuth::authenticate(str_replace($request->header('Authorization'), 'Bearer ', ''));
        storeLogActivity('get_authenticated', $user->id);
		return  response()->json(['user' => $user], 200);
    }

    public function getUserAuthenticated($token) 
    {
        $user = JWTAuth::authenticate($token);
		return $user;
    }

    public function testEmail(Request $request)
    {
        $title = $request->title;
        $sender = $request->sender;
        $receiver = $request->receiver;
        $message = $request->message;
        $footer = $request->footer;

        $result = sendEmail($sender, $receiver, $message, $title, $footer);
        if ($result == 'email_success') {
            return  response()->json([
                'message' => __('messages.email_success'),
                'status' => 'success',
            ], 200);
        } else {
            return  response()->json([
                'message' => __('messages.email_error'),
                'status' => 'error',
            ], 400);
        }
    }
}
