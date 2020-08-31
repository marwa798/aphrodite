<div id="modalEdit-{{ $data->id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="custom-validation form-data" data-id="{{ $data->id }}">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">Edit Category [ {{ $data->name}} ]</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $data->name}}" placeholder="Name">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" placeholder="Description" value="{{ $data->description }}">
                            {{ $data->description }}
                        </textarea>
                    </div>
              
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="submit-btn btn btn-primary waves-effect waves-light">Update</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->