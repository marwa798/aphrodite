<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Collection;
use App\Pictures;
use App\Tag;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CommonController extends Controller 
{
    /**
     * Get All Categories 
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
     public function getCategories(Request $request)
     {
        
        $json['message'] = '';
        $json['data']['categories'] = Category::get();

        return response()->json($json, 200);
     }
     
     /**
     * Get All Tags 
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
     public function getTags(Request $request)
     {
        
        $json['message'] = '';
        $json['data']['tags'] = Tag::get();

        return response()->json($json, 200);
     }
}