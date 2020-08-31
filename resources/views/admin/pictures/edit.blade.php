
@extends('admin.main')

{{-- Page Title --}}
@section('title', 'edit Picture')

@section('content')

    <form class="custom-validation form-data" data-id="{{ $data->id }}">
        <div class="modal-header">
            <h5 class="modal-title mt-0" id="myModalLabel">Edit Picture [ {{ $data->image}} ]</h5>
            <div class="col-lg-4">
                <div class="card">
                    <img class="card-img-top img-fluid" src="{{getImage($data->image)}}" alt="Card image cap">
                </div>

            </div>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="form-group" >
                <label>Category</label>
                <select name="category_id" id="category_id" class="form-control" >
                    <option>
                        Select Category
                    </option>
                    @foreach($categories as $category)
                        <option @if($data->category_id == $category->id) selected="selected" @endif value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>


            <div class="form-group">
                <label>Caption</label>
                <textarea class="form-control" name="caption" placeholder="">{{$data->caption ?? ''}}</textarea>
        </div>


            <div class="form-group">
                <select class="form-control select-tags" name="tags[]" multiple="multiple">
                    @foreach($tags as $tag)
                    <option @if(in_array($tag->id, $tagsPictures)) selected="selected" @endif  value="{{ $tag->name }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
        {{--<div class="form-group">--}}
            {{--<label>Tags</label>--}}
            {{--<textarea class="form-control" name="tag" placeholder="Tags" ></textarea>--}}
            {{--</div>--}}


        </div>
        <div class="modal-footer">
            <button type="button"  class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            <button type="submit" class="submit-btn btn btn-primary waves-effect waves-light">Update</button>
        </div>
    </form>

@endsection

@section('scripts')
    <script>
    
        $(".select-tags").select2({
            tags: true,
            tokenSeparators: [',', ' ']
        })
        $('.form-data').submit(function(e){
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            var self = this;

            formData.append('_method', 'PUT')

            $.ajax({
                url:"{{adminUrl('pictures/' . $data->id)}}",
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
                        window.location.href = "{{adminUrl('pictures')}}";
                    }
                }
            })
        })
    </script>
@endsection