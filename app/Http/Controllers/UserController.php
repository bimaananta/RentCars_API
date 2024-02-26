<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function updateProfile(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "firstname" => "required",
            "lastname" => "required",
            "username" => "required|min:3",
            "email" => "required|email",
            "telephone" => "required|numeric"
        ]);

        if($validation->fails()){
            $this->createResponseValidate($validation->errors());
        }

        $user = User::find(Auth::user()->id);

        try{
            $updateUser = $user->update($request->all());
        }catch(\Exception $e){
            return $this->createResponseAPI("failed", "Failed to update user profile" + $e, null, 401);
        }

        return $this->createResponseAPI("success", "Update user profile success", User::find(Auth::user()->id), 201);
    }
}
