<div id="modalEdit-{{ $data->id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="custom-validation form-data" data-id="{{ $data->id }}">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">Edit User [ {{ $data->name}} ]</h5>
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
                        <label>Email</label>
                        <input type="email" parsley-type="email" id="email" value="{{ $data->email}}"  class="form-control" required="" name="email" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" required="" name="phone" value="{{ $data->phone }}"  id="phone" placeholder="Phone">
                    </div>

                    <div class="form-group">
                        <label>linkedin</label>
                        <input type="text" class="form-control" name="linkedin" value="{{ $data->linkedin }}"  id="linkedin" placeholder="linkedin Url">
                    </div>

                    <div class="form-group">
                        <label>instagram</label>
                        <input type="text" class="form-control" name="instagram" value="{{ $data->instagram }}" id="instagram" placeholder="instagram Url">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control select2" name="status" style="width: 100%">
                            <option @if($data->status)
                                selected
                            @endif value="1"> Enable </option>
                            <option @if(!$data->status)
                                selected
                            @endif  value="0"> Disable </option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <div>
                            <input type="password" name="password"  id="password" class="form-control" 
                                    placeholder="Password"/>
                        </div>
                        <div class="mt-2">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                    placeholder="Re-Type Password"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit"  class="submit-btn btn btn-primary waves-effect waves-light">Update</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->