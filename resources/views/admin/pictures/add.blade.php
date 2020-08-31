<div id="modalAddNew" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="custom-validation form-data" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">Add New Picture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{--<div class="form-group">--}}
                        {{--<label>Name</label>--}}
                        {{--<input type="text" class="form-control" name="name" id="name" placeholder="Name">--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <label>Picture</label>
                        <input type="file" name="image" id="image">
                    </div>

                    <div class="form-group" >
                        <label>Category</label>
                        <select name="category_id" id="category_id" class="form-control" >
                            <option>
                                Select Category
                            </option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" >
                        <label>Collection</label>
                        <select name="collection_id" id="collection_id" class="form-control" >
                            <option>
                                Save to collection
                            </option>
                            @foreach($collections as $collection)
                                <option value="{{$collection->id}}">{{$collection->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Caption</label>
                        <textarea class="form-control" name="caption" placeholder="Caption" ></textarea>
                    </div>

                    <div class="form-group">
                        <label>Tags</label>
                        <textarea class="form-control" name="tags" placeholder="Tags" ></textarea>
                    </div>

                    {{-- --}}
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="submit-btn btn btn-primary waves-effect waves-light">Add</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->