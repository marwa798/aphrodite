<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Admins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // View All Admins

        return view('admin.admins.index');
    }
    /**
     * Get All Data 
     *
     * @return Json
     */
    public function getData(Request $request)
    {
        $modal = new Admins();

        $columns = array( 
            0 =>'id', 
            1 =>'name',
            2 => 'email',
            3 => 'phone',
            4 => 'status',
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
            ->orWhere('name', 'LIKE',"%{$search}%")
            ->orWhere('email', 'LIKE',"%{$search}%")
            ->orWhere('phone', 'LIKE',"%{$search}%");

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
                $delete =  adminUrl('admins/' . $item->id);
                $token = csrf_token();
                $nestedData['id'] = $item->id;
                $nestedData['name'] = $item->name;
                $nestedData['email'] = $item->email;
                $nestedData['phone'] = $item->phone;
                $nestedData['status'] = $item->status ? '<span class="badge badge-pill badge-primary">Enable</span>' : '<span class="badge badge-pill badge-danger">Disable</span>';
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
            'email'    => 'required|email|unique:admins',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        unset($data['password_confirmation']);
		$data['password'] = bcrypt(request('password'));
		Admins::create($data);
		session()->flash('success', 'Success Add New Admin');
        
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
        // return view('admin.admins.edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admins::find($id);
		return view('admin.admins.edit', compact('admin'));
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
            'email'    => 'required|email|unique:admins,email,'.$id,
            'password' => 'sometimes|nullable|confirmed|min:6',
            'phone' => 'required',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['sd'=>$data,'errors'=>$validator->errors()]);
        }

        unset($data['password_confirmation']);
        unset($data['_method']);

        $data['password'] = Admins::find($id)->password;
		if ($request->has('password')) {
			$data['password'] = bcrypt(request('password'));
		}
		Admins::where('id', $id)->update($data);
        session()->flash('success', 'Admin is successfully Edit');
        
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
        Admins::find($id)->delete();
		session()->flash('success', 'DELETED Success');
		return response()->json(['success'=>'Success Deleted']);
    }
}
