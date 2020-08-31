<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // View All Tag

        return view('admin.tag.index');
    }
    /**
     * Get All Data 
     *
     * @return Json
     */
    public function getData(Request $request)
    {
        $modal = new Tag();

        $columns = array( 
            0 => 'id', 
            1 => 'name',
            5 => 'created_at',
            6 => 'id',
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
            ->orWhere('name', 'LIKE',"%{$search}%");

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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            'name'     => 'required',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        Tag::create($data);
		session()->flash('success', 'Success Add New Tag');
        
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
        // return view('admin.Tag.edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Tag::find($id);
		return view('admin.tag.edit', compact('data'));
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
            'name'     => 'required',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        unset($data['_method']);

 
		Tag::where('id', $id)->update($data);
        session()->flash('success', 'Tag was successfully Edited');
        
        return response()->json(['success'=>'Data is successfully Edit']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tag::find($id)->delete();
		session()->flash('success', 'DELETED Success');
		return response()->json(['success'=>'Success Deleted']);
    }
}
