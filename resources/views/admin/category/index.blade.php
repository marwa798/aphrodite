@extends('admin.main')

{{-- Page Title --}}
@section('title', 'Category')

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
                <table id="datatable" data-url="{{ adminUrl('category/all') }}" class="table table-bordered dt-responsive nowrap"
                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Date Created</th>
                            <th>Options</th>
                        </tr>
                    </thead>                    
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div>


@include('admin.category.add')

@endsection

@section('scripts')
    <!-- Datatable init js -->
    <script>
        $(document).ready(function() {
            function submitForm()
            {
                $('.form-data').submit(function(e){
                    e.preventDefault();
                    var formData = new FormData($(this)[0]);
                    var self = this;
                    var id = $(this).data('id');
                    var url = $(this).data('id') ? "{{adminUrl('category')}}/" + id : "{{adminUrl('category')}}";
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
            }
            
            // Data Table Delete Function
            function deleteItem()
            {
                $(".deleteItem").click(function(){
                    var id = $(this).data("id");
                    console.log("ss");
                    if (confirm("Are You Sure To Delete ?")) 
                    {
                        $.ajax(
                        {
                            url: "category/"+id,
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
            }

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
                    deleteItem();

                    // Init Submit Form Function OnLoad DataTable
                    submitForm();
                },
            })
        });

    </script>
    
@endsection