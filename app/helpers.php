<?php 

// Var Gurd Name

if( !function_exists('admin') )
{
    function admin(){
        return auth()->guard('admins')->user();
    }
}

if( !function_exists('adminGurd') )
{
    function adminGurd(){
        return auth()->guard('admins');
    }
}

if(!function_exists('authApi'))
{
    function authApi()
    {
        return auth()->guard('api');
    }
}
if( !function_exists('adminUrl') ){
    function adminUrl($route = '')
    {
        return url('admin/'. $route);
    }
}

/**
 * This Function Get Image Url 
 * 
 * @param $picName
 * @param $defualt 
 * 
 * @return string
 */
if( !function_exists('getImage')) {
    function getImage($picName, $defualt = 'avatar.jpg')
    {
        $baseBase = 'app/public/';

        if( $picName && file_exists(storage_path($baseBase . $picName)))
        {
            return config('app.url') . 'storage/' . $baseBase . $picName;
        }
        return config('app.url') . 'storage/' . $baseBase . $defualt;
    }
}