<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.collection.index');
    }
    /**
     * Get All Data
     *
     * @return Json
     */
    public function getData(Request $request)
    {
        $modal = new Collection();

        $columns = array(
            0 => 'id',
            1 => 'name',
            5 => 'created_at',
            6 => 'id'
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
                $urlShowPictures = adminUrl('pictures') . '?collection_id=' . $item->id;
                $nestedData['id'] = $item->id;
                $nestedData['name'] = $item->name;
                $nestedData['created_at'] = date('j M Y h:i a',strtotime($item->created_at));
                $nestedData['options'] = "{$this->edit($item->id)}
                 <a href='{$urlShowPictures}' class=''><span class='fa fa-eye'></span></a>&emsp;
                <a href='javascript:void(0)' data-toggle='modal' data-target='#modalEdit-{$item->id}' class='editItem' title='EDIT'><span class='bx bx-pencil'></span></a>
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

        Collection::create($data);
        session()->flash('success', 'Success Add New Collection');

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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Collection::find($id);
        return view('admin.collection.edit', compact('data'));
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


        Collection::where('id', $id)->update($data);
        session()->flash('success', 'Collection was successfully Edited');

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
        Collection::find($id)->delete();
        session()->flash('success', 'DELETED Success');
        return response()->json(['success'=>'Success Deleted']);
    }
}