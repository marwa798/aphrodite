<?php

namespace App\Http\Controllers\Api;

use App\Follow;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * This funtion Add Follow User
     * 
     * @param \Illuminate\Http\Request $request 
     * 
     * @return json
     */
    public function addFollow(Request $request)
    {
        $json = [];

        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|integer',
        ]);

        if ($validator->fails()) {

            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 200);
        }
        
        $follow_id = $request->get('user_id');
        $current_user_id = authApi()->user()->id;

        
        if (!Users::where('id', '=', $follow_id)->exists()) {

            $json['message'] = 'This User Don\'t Exists';
            $json['isFollowed'] = false;
            $json['data'] = [];

            return response()->json($json, 200);
        }

        if(Follow::where('user_id', $current_user_id)->where('followed_id', $follow_id)->first())
        {
            $json['message'] = 'You Are Already Followed This User';
            $json['isFollowed'] = true;
            $json['data'] = [];

            return response()->json($json, 200);
        }

        Follow::create([
            'user_id' => $current_user_id,
            'followed_id' => $follow_id
        ]);

        $json['message'] = 'Success Followed';
        $json['isFollowed'] = true;
        $json['data'] = [];

        return response()->json($json, 200);
    }

    /**
     * Cancel follwo For User
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function deleteFollow(Request $request)
    {
        $json = [];

        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|integer',
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 200);
        }
        
        $follow_id = $request->get('user_id');
        $current_user_id = authApi()->user()->id;

        if (!Users::where('id', '=', $follow_id)->exists()) {

            $json['message'] = 'This User Don\'t Exists';
            $json['isFollowed'] = false;
            $json['data'] = [];

            return response()->json($json, 200);
        }

        if(!$follow = Follow::where('user_id', $current_user_id)->where('followed_id', $follow_id)->first())
        {
            $json['message'] = 'You Don\'t Follow This User';
            $json['isFollowed'] = false;
            $json['data'] = [];
            return response()->json($json, 200);
        }

        $follow->delete();

        $json['message'] = 'Success Cancel follow';
        $json['isFollowed'] = false;
        $json['data'] = [];

        return response()->json($json, 200);
    }

    /**
     * This funtion Return Followers for Current User
     * 
     * @return json
     */
    public function getFollowers()
    {
        $data = [];
        
        foreach(Users::find(authApi()->user()->id)->followers()->get()  as $user){
            $user->profile_pic = getImage($user->profile_pic);
            
            $user['isFollowed'] = authApi()->user() ? 
                                        Follow::where('followed_id', $user->id)->where('user_id', authApi()->user()->id)->first() 
                                                ? true : false : false;
            $data[] = $user;
        }
        
        $json['data']['followers'] =  $data;

        return response()->json($json, 200);
    }

    /**
     * This funtion Return Followerings for Current User
     * 
     * @return json
     */
    public function getFollowings()
    {
        $data = [];
        
        foreach(Users::find(authApi()->user()->id)->followings()->get()  as $user){
            $user->profile_pic = getImage($user->profile_pic);
            
            $user['isFollowed'] = authApi()->user() ? 
                                        Follow::where('followed_id', $user->id)->where('user_id', authApi()->user()->id)->first() 
                                                ? true : false : false;
            $data[] = $user;
        }
        
        $json['data']['followings'] = $data;

        return response()->json($json, 200);
    }
}