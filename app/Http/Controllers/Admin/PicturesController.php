<?php

namespace App\Http\Controllers\Admin;

use App\Admins;
use App\PictureCollection;
use App\Category;
use App\Collection;
use App\Http\Controllers\Controller;
use App\Pictures;
use App\Users;
use App\Tag;
use App\PictureTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PicturesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $ids = [];
        
        $picturesCollection = PictureCollection::where('collection_id', $request->get('collection_id'))->get();
        
        foreach($picturesCollection as $picCollecion){
            $ids[] = $picCollecion->picture_id;
        }
        

        $categories = Category::get();
        
        $pictures = $ids ? Pictures::find($ids) : Pictures::get();
        
        $collections = Collection::get();
        
        
        
//        $users = Users::get();

        return view('admin.pictures.index', compact('categories', 'pictures','collections'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getData(Request $request)
    {
        
        
        $modal = new Pictures();

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'user_id',
            5 => 'image',
            6 => 'category_id',
            7 => 'caption',
            8 => 'collection',
            9 => 'tags',
            10 => 'created_at',
            11 => 'id',
        );


        $totalData = $modal->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $data = $modal->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $data_query =   $modal->where('id','LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->orWhere('user_id', 'LIKE',"%{$search}%")
                ->orWhere('category_id', 'LIKE',"%{$search}%") //?????
                ->orWhere('caption', 'LIKE',"%{$search}%")
                ->orWhere('collection', 'LIKE',"%{$search}%");


            $data =  $data_query
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = $data_query->count();
        }

        $all_data = array();
        if(!empty($data))
        {
            foreach ($data as $item)
            {
                $nestedData['id'] = $item->id;
                $nestedData['name'] = $item->name;
                $nestedData['user_id'] = $item->user_id;
                $nestedData['image'] = $item->image;
                $nestedData['category_id'] = $item->category_id;
                $nestedData['caption'] = $item->caption;
                $nestedData['collection'] = $item->collection;
                $nestedData['tags'] = $item->tags;
                $nestedData['created_at'] = date('j M Y h:i a',strtotime($item->created_at));
                $nestedData['options'] =
                    "{$this->edit($item->id)}
                &emsp;<a href='javascript:void(0)' data-toggle='modal' data-target='#modalEdit-{$item->id}' class='editItem' title='EDIT'><span class='bx bx-pencil'></span></a>
                <a href='javascript:void(0)' data-id='{$item->id}' class='deleteItem'><span class='bx bx-trash-alt'></span></a>";
                $all_data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $all_data
        );


        echo json_encode($json_data);

    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'image'  => 'required|image',
            'category_id' => 'required|integer'

        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $file = $request->file('image');
        $file_change_name = time() . '_' . $file->getClientOriginalName();
        Storage::disk('public')->put($file_change_name, File::get($file));

        $data_insert = [
            'user_id' => 0,
            'category_id' => $data['category_id'],
            'caption' => $data['caption'],
            'image'   => $file_change_name
        ];


        Pictures::create($data_insert);
        session()->flash('success', 'Success Add New Picture');

        return response()->json(['success'=>'Data is successfully added']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $tagsPictures = [];

        $data = Pictures::find($id);
        
        $categories = Category::get();
        $collections = Collection::get();
        $pictures = Pictures::get();
        $tags = Tag::get();
        
        $pictureTags = PictureTag::where('picture_id', $id)->select('tag_id')->get();

        foreach($pictureTags as $picTag){
            $tagsPictures[] = $picTag->tag_id;
        }
        
        if(!$data){
            echo 'Not Found';die;
        }

        $users = Users::find($id);

        return view('admin.pictures.edit' , compact('data', 'tagsPictures', 'tags', 'categories','users','collections','pictures'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        
        $validator = Validator::make($data, [
            'category_id' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        
        $tags = $request->get('tags');

        unset($data['_method']);
        unset($data['tags']);
        unset($data['collection_id']);

        Pictures::where('id', $id)->update($data);
        
        if($tags){
            PictureTag::where('picture_id', $id)->delete();
            foreach($tags as $tag){
                if($tagCheck = Tag::where('name', $tag)->first()){
                    PictureTag::create([
                        'picture_id' => $id,
                        'tag_id' => $tagCheck->id
                    ]);   
                }else{
                    $tagCheck = Tag::create([
                        'name' => $tag,
                    ]);
                    
                    PictureTag::create([
                        'picture_id' => $id,
                        'tag_id' => $tagCheck->id
                    ]);
                }
            }
        }
        

        session()->flash('success', 'Picture was successfully Edited');

        return response()->json(['success'=>'Data was successfully Edited']);

    }

    /**
     * This funtion add Image to collection
     *
     * @param \Illuminate\Http\Request  $request
     * @return
     */
    public function SaveToCollection(Request $request)
    {
        $data = $request->all(); // [ 'ID' => 4 ] /--/ $request->GET('ID')

        if($data['collection_id']){
            foreach($data['collection_id'] as $collection_id){
                PictureCollection::create([
                    'picture_id' => $data['id'],
                    'collection_id' => $collection_id
                ]);
            }
        }

        session()->flash('success', 'Picture was successfully added to collection');
        return response()->json(['success'=>'Data was successfully saved']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pictures::find($id)->delete();
        session()->flash('success', 'DELETED Success');
        return response()->json(['success'=>'Success Deleted']);
    }
}
