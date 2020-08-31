@extends('admin.main')

{{-- Page Title --}}
@section('title', 'Pictures')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @include('admin.particles.messages')
                <div class="buttons-group">
                    <button type="button" data-toggle="modal" data-target="#modalAddNew" class="btn btn-primary waves-effect waves-light float-right">
                        <i class="bx bx-plus font-size-16 align-middle mr-2"></i> Add New
                    </button>
                </div>
                {{--<table id="datatable" data-url="{{ adminUrl('pictures/all') }}" class="table table-bordered dt-responsive nowrap"--}}
                {{--style="border-collapse: collapse; border-spacing: 0; width: 100%;">--}}
                    {{--<thead>--}}
                        {{--<tr>--}}
                            {{--<th>ID</th>--}}
                            {{--<th>Name</th>--}}
                            {{--<th>Date Created</th>--}}
                            {{--<th>Options</th>--}}
                        {{--</tr>--}}
                    {{--</thead>--}}
                {{--</table>--}}

                <div class="row">
                    @foreach($pictures as $pic)

                        <div class="col-lg-4">
                            <div class="card">
                                <img class="card-img-top img-fluid" src="{{getImage($pic->image)}}" alt="Card image cap">
                                <div class="card-body">
                                    <h4 class="card-title mt-0">{{$pic->name}}</h4>
                                    <h4 class="card-title mt-0">{{$pic->user_id}}</h4>
                                    <p class="card-text">{{$pic->caption}}</p>
                                    <p class="card-text">
                                        <small class="text-muted">{{$pic->created_at}}</small>
                                    </p>

                                    <a href="{{ adminUrl('pictures/'. $pic->id .'/edit') }}" class="card-link">Edit picture</a>
                                    <a href="javascript:void(0)" data-id="{{ $pic->id }}" class="deleteItem card-link">Delete</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


            </div>
        </div>
    </div> <!-- end col -->
</div>




@include('admin.pictures.add')

@endsection

@section('scripts')
    <!-- Datatable init js -->
    <script>
        $(document).ready(function() {

            $('.form-data').submit(function(e){
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                var self = this;
                var id = $(this).data('id');
                var url = $(this).data('id') ? "{{adminUrl('pictures')}}/" + id : "{{adminUrl('pictures')}}";
                if(id)
                {
                    formData.append('_method', 'PUT')
                }
                $.ajax({
                    url: url,
                    type: "POST",
                    contentType: false,
                    processData: false,
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    data: formData,
                    beforeSend: function() {
                        $(".submit-btn").attr('disabled','disabled');
                    },
                    success: function (result)
                    {
                        $(".submit-btn").removeAttr('disabled');
                        if(result.errors)
                        {
                            $('.form-group .text-danger').remove();

                            $.each(result.errors, function(key, value){
                                $(self).find('#' + key).addClass('parsley-error').after('<span class="text-danger">'+ value +'</span>');
                            });
                        }
                        else
                        {
                            location.reload();
                        }
                    }
                })
            })


            $(".deleteItem").click(function(){
                var id = $(this).data("id");
                console.log("ss");
                if (confirm("Are You Sure To Delete ?"))
                {
                    $.ajax(
                    {
                        url: "pictures/"+id,
                        type: 'DELETE',
                        dataType: "JSON",
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},

                        success: function ()
                        {
                            location.reload();
                        }
                    });
                }
            });


            var dataTable = $('#datatable');
            var allDataUrl = dataTable.data('url');
            dataTable.DataTable({
                "processing": true,
                "serverSide": true,
                "ajax":{
                    "url": allDataUrl,
                    "dataType": "json",
                    "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    "type": "POST",
                },
                "columns": [
                    { "data": "id" },
                    { "data": "name" },
                    { "data": "created_at" },
                    { "data" : "options"}
                ],
                "drawCallback": function( settings ) {
                    // Load Function Delete 

                },
            })
        });

    </script>
    
@endsection