<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Admins;
use App\Category;
use App\Pictures;
use App\Tag;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];

        $data['users_count'] = Users::count();
        $data['categories_count'] = Category::count();
        $data['tags_count'] = Tag::count();
        $data['pictures_count'] = Pictures::count();
        return view('admin.dashboard', compact('data'));
    }
}