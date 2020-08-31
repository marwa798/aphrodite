<div id="modalEdit-{{ $admin->id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="custom-validation form-data" data-id="{{ $admin->id }}">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">Edit Admin [ {{ $admin->name}} ]</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $admin->name}}" placeholder="Name">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" parsley-type="email" id="email" value="{{ $admin->email}}"  class="form-control" required="" name="email" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" required="" name="phone" value="{{ $admin->phone }}"  id="phone" placeholder="Phone">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control select2" name="status" style="width: 100%">
                            <option @if($admin->status)
                                selected
                            @endif value="1"> Enable </option>
                            <option @if(!$admin->status)
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
                    <button type="submit" class="submit-btn btn btn-primary waves-effect waves-light">Add</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->