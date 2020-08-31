<?php


Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    
    // Change Gurd Auth To Admins 
    Config::set('auth.defaults.guard', 'admins');

    // Check If Is Admin Login
    Route::group(['middleware' => 'admins'], function () {

        Route::get('/', 'DashboardController@index');

        // Logout
        Route::any('/logout', 'AdminAuth@logout');

        // Admins Resource Route
        Route::resource('/admins', 'AdminsController');
        Route::post('/admins/all', 'AdminsController@getData');

        // Users Resource Route
        Route::resource('/users', 'UsersController');
        Route::post('/users/all', 'UsersController@getData');

        // Category Resource Route
        Route::resource('/category', 'CategoryController');
        Route::post('/category/all', 'CategoryController@getData');

        // Tag Resource Route
        Route::resource('/tag', 'TagController');
        Route::post('/tag/all', 'TagController@getData');

        Route::resource('/pictures', 'PicturesController');
        Route::post('/pictures/all', 'PicturesController@getData');
        Route::post('/pictures/save', 'PicturesController@SaveToCollection');

        Route::resource('/collection', 'CollectionController');
        Route::post('/collection/all', 'CollectionController@getData');

    });

    // Login Pages
    Route::get('/login', 'AdminAuth@login');
    Route::post('/login', 'AdminAuth@doLogin');

    // Reset Password Pages
    Route::get('/reset/password', 'AdminAuth@resetPassword');
    Route::post('/reset/password', 'AdminAuth@forgot_password_post');
    Route::get('reset/password/{token}', 'AdminAuth@reset_password');
    Route::post('reset/password/{token}', 'AdminAuth@reset_password_final');
    
});