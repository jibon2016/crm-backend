<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        $users = User::all();
        return send_response('Success', $users);
    }
    public function login (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return send_errors('Validation error', $validator->errors(), 422);
        }

        $credential = $request->only('email', 'password');

        if (Auth::attempt($credential)){
            $user  = Auth::user();
            $data = [
                "name" => $user->name,
                "access_token" =>  $user->createToken('accessToken')->accessToken
            ];
            return send_response('You are Successfully login', $data);
        }else{
            return send_errors('Unauthorised', '', 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'name' => 'required|min:4',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:6'
        ]);

        if ($validator->fails()){
            return send_errors('Validation error', $validator->errors(), 422);
        }

        try {
            $user = User::create([
                'name'      => $request->name,
                'email'      => $request->email,
                'password'      => Hash::make($request->password)
            ]);

            $data = [
                "name" => $user->name,
                "email" => $user->email,
            ];
            return send_response('User Registration Success', $data);

        } catch (Exception $e ) {
            return send_errors($e->getMessage(), $e->getCode() );
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();

        return send_errors('Successfully logged out', '');
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user){
            return send_response('Success', $user);
        }else{
            return send_errors('Data Not Found', '');
        }
    }
}
