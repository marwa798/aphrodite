<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function () {
    // Auth
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');

    Route::group(['middleware' => ['auth_api']], function () {

        // User 
        Route::post('/update_account', 'AuthController@updateUser');
        Route::any('/me', 'AuthController@getAuthenticatedUser');
        Route::any('/password', 'AuthController@changePassword');
        Route::any('/logout', 'AuthController@logout');
        
        // Follow Routes
        Route::post('/follow', 'UserController@addFollow');
        Route::delete('/follow', 'UserController@deleteFollow');
        Route::get('/followers', 'UserController@getFollowers');
        Route::get('/followerings', 'UserController@getFollowings');
        
        // Images And Collections     
        Route::post('/collection', 'ImagesController@createCollection');
        Route::get('/collections', 'ImagesController@getCollections');
        Route::delete('/collection', 'ImagesController@deleteCollection');
        Route::put('/collection', 'ImagesController@updateCollection');
        Route::post('/upload_image', 'ImagesController@uploadImage');
        Route::post('/enhance_image', 'ImagesController@enhanceImage'); //enhance
        Route::post('/update_image', 'ImagesController@updateImage');
        Route::delete('/delete_image', 'ImagesController@deleteImage');
        Route::post('/save_to_collection', 'ImagesController@saveImageToCollection');
        Route::delete('/remove_from_collection', 'ImagesController@removeImageToCollection');

        Route::get('/following_images', 'ImagesController@getFollowingImages');

        // LIKES Images
        Route::post('/like', 'ImagesController@addLike');
        Route::delete('/like', 'ImagesController@deleteLike');
        Route::get('/liked_pictures', 'ImagesController@getLikedPictures');

    });

    Route::get('/image', 'ImagesController@getImage');
    Route::get('/images', 'ImagesController@getImages');
    Route::get('/top_liked', 'ImagesController@getTopLiked');
    
    // Common Controller
    Route::get('/categories', 'CommonController@getCategories');
    Route::get('/tags', 'CommonController@getTags');
    
    
    //reset password

    Route::any('/password/email','ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset','ResetPasswordController@reset');

    //downloadimage
    Route::get('image/save/{id}','ImagesController@downloadImage');


Route::get('/clear', function() {

   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');
   
   if( function_exists("proc_open")){
    return "Hi !";
}

   return "Cleared!";

});
});
