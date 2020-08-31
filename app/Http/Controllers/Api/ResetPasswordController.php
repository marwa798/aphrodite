<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Users;
use App\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;


class ResetPasswordController extends Controller
{
    public function reset(Request $request){
    $json = [];

    $validator = Validator::make($request->all(), [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);

    if ($validator->fails()) {

        $json['errors'] = $validator->errors()->all();

        return response()->json($json, 422);
    }

    $email = $request->email;
    $token = $request->token;


    $p = PasswordReset::where('email', $email)->first();
    $u= Users::where('email',$email)->first();

        if($p != NULL  && $u)
        {
            $u->password = Hash::make($request->password);
            $u->save();

            return response()->json(['message'=>'reset complete'],200);

        }else{
            return response()->json(['message'=>'try again to send reset email password'],200);
        }




    }
}
