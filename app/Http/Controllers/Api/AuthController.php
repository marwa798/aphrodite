<?php

namespace App\Http\Controllers\Api;

use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request) 
    {

        if(authApi()->user()){

            $json['errors'][] = 'You Are Logged';

            return response()->json($json, 200);
        }
        
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()) {

            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }

        if (! $token = authApi()->attempt($credentials)) {

            $json['errors'][] = 'Email Or Password Incorrect';

            return response()->json($json, 401);
        }

        $credentials['status'] = 1;
        if(! $token = authApi()->attempt($credentials)){

            $json['errors'][] = 'Your Account Not Active';
            return response()->json($json, 401);
        }

        $user = authApi()->user();

        unset($user['status']);
        unset($user['created_at']);
        unset($user['updated_at']);
        unset($user['remember_token']);
        unset($user['password']);
        $user->profile_pic = getImage($user->profile_pic);
        $json['data']['token'] = $token;
        $json['data']['user'] = $user;
        

        return response()->json($json, 200);
    }

    public function register(Request $request)
    {
        $json = [];
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required',
            'last_name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'profile_pic' => 'nullable|image'
        ]);

        if($validator->fails()) {

            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }



        $user = Users::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone') ?? '',
            'linkedin' => $request->get('linkedin') ?? '',
            'instagram' => $request->get('instagram') ?? '',
            'password' => Hash::make($request->get('password')),

        ]);


        if ($request->hasFile('profile_pic'))
        {
            $user = Users::where('id', $user['id'])->firstOrFail();
            $file = $request->file('profile_pic');
            $file_change_name =  $user['id']  . '_' . time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->put($file_change_name, File::get($file));
            $user->profile_pic = $file_change_name;

            $user->save();
        }
        

        $json['data']['token'] = authApi()->attempt(['email'=> $request->get('email'), 'password'=>$request->get('password')]);

        $user = authApi()->user();

        unset($user['status']);
        unset($user['created_at']);
        unset($user['updated_at']);
        unset($user['remember_token']);
        unset($user['password']);
        $user['profile_pic'] = getImage($user['profile_pic']);

        $json['data']['user'] = $user;

        return response()->json($json, 200);
    }

    public function updateUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name'  => 'nullable|min:3|max:15',
            'last_name'  => 'nullable|min:3|max:15',
            'email'  => 'nullable|email|unique:users,email,' .authApi()->user()->id,
            'phone' => 'nullable',
            'instagram' => 'nullable|string|url',
            'linkedin' => 'nullable|string|url',
            'website' => 'nullable|string|url',
            'bio' => 'nullable|string|min:3|max:200',
            'profile_pic' => 'nullable|image'
        ]);

        if ($validator->fails()) {

            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }

        $user = Users::where('id', authApi()->user()->id)->firstOrFail();

        if ($request->hasFile('profile_pic')) 
        {
            $file = $request->file('profile_pic');

            $file_change_name =  authApi()->user()->id  . '_' . time() . '_' . $file->getClientOriginalName();

            Storage::disk('public')->put($file_change_name, File::get($file)); 

            $user->profile_pic = $file_change_name; 

        }
        if($request->has('first_name') && $request->get('first_name'))
            $user->first_name = $request->get('first_name');

        if($request->has('last_name') && $request->get('last_name'))
            $user->last_name = $request->get('last_name');

        if($request->has('email') && $request->get('email'))
            $user->email = $request->get('email');

        if($request->has('phone') && $request->get('phone'))
            $user->phone = $request->get('phone');

        if($request->has('linkedin') && $request->get('linkedin'))
            $user->linkedin = $request->get('linkedin');

        if($request->has('instagram') && $request->get('instagram'))
            $user->instagram = $request->get('instagram');

        if($request->has('website') && $request->get('website'))
            $user->website = $request->get('website');

        if($request->has('bio') && $request->get('bio'))
            $user->bio = $request->get('bio');

        $user->save();

        unset($user['status']);
        unset($user['created_at']);
        unset($user['updated_at']);
        unset($user['remember_token']);
        unset($user['password']);

        $user['profile_pic'] = getImage($user['profile_pic']);


        $json['data']['user'] = $user;

        return response()->json($json, 200);
    }

    public function changePassword(Request $request)
    {

        $results = Validator::make($request->all(), [
            'old_password' => 'required|min:6',
            'password'  => 'required|min:6|confirmed'
        ]);


        if( ! $results->fails()){

            $user = authApi()->user();

            if(Hash::check($request->get('old_password'), $user->password)){

                $user->password = Hash::make($request->get('password'));
                $user->save();

                return response()->json([
                    'message'   => "Success Change Password",
                    'data'      =>
                        ['user' => $user]
                ], 200);
            } else {
                return response()->json([
                    'errors'    => [
                        'Old password is wrong'
                    ]
                ], 400);
            }
        } else {
            return response()->json([
                'errors'    => $results->errors()
            ], 401);
        }

    }


    public function getAuthenticatedUser()
    {

        $json = [];
        $user = authApi()->user();
        unset($user['status']);
        unset($user['created_at']);
        unset($user['updated_at']);
        unset($user['remember_token']);
        unset($user['password']);
        $user['profile_pic'] = getImage($user['profile_pic']);

        $json['data'] = $user;
        return response()->json($json, 200);     
    }

    public function logout()
    {
        $json = [];
        authApi()->logout();

        return response()->json(['message' => 'Successfully logged out', 'data' => []], 200);
    }

}
