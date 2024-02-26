<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "username" => "required|unique:users",
            "firstname" => "required|max:255",
            "lastname" => "required",
            "telephone" => "required",
            "email" => "required|email",
            "password" => "required|min:8|confirmed"
        ]);

        if($validation->fails()){
            return response()->json([
                "status" => "failed",
                "message" => "Invalid Field",
                "errors" => $validation->errors()
            ], 403);
        }

        $input = $request->except('password_confirmation');
        $input["password"] = Hash::make($request->password);

        $user = User::create($input);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "status" => "success",
            "message" => "Register success",
            "token" => $token,
            "user" => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "username" => "required",
            "password" => "required|min:8"
        ]);

        if($validation->fails()){
            return response()->json([
                "status" => "failed",
                "message" => "Invalid Field",
                "errors" => $validation->errors()
            ], 403);
        }

        $user = User::where('username', $request->username)->firstOrFail();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                "status" => "failed",
                "message" => "Wrong username or password"
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "status" => "success",
            "message" => "Login success",
            "token" => $token
        ], 200);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "status" => "success",
            "message" => "Log out success"
        ], 200);
    }
}
 