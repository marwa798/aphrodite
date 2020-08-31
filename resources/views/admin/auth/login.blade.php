@include('admin.particles.head')
  <body>
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-soft-primary">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary">Welcome Back !</h5>
                                            <p>Sign in to continue to Sha-Team Project.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{ url('/')}}/dashboard_assets/images/profile-img.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0"> 
                                <div>
                                    <a href="{{ url('admin') }}">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ url('/')}}/dashboard_assets/images/logo.svg" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2">
                                    @include('admin.particles.messages')
                                    <form class="form-horizontal" action="" method="POST">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" value="{{ Request::old('email') }}" name="email" class="form-control" id="email" placeholder="Enter email">
                                        </div>
                
                                        <div class="form-group">
                                            <label for="userpassword">Password</label>
                                            <input type="password" name="password" class="form-control" id="userpassword" placeholder="Enter password">
                                        </div>
                
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="remeberme" value="1" class="custom-control-input" id="customControlInline">
                                            <label class="custom-control-label" for="customControlInline">Remember me</label>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
                                        </div>
            
                                        <div class="mt-4 text-center">
                                            <a href="{{ adminUrl('reset/password') }}" class="text-muted"><i class="mdi mdi-lock mr-1"></i> Forgot your password?</a>
                                        </div>
                                    </form>
                                </div>
            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- JAVASCRIPT -->
    <script src="{{ url('/')}}/dashboard_assets/libs/jquery/jquery.min.js"></script>
    <script src="{{ url('/')}}/dashboard_assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('/')}}/dashboard_assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="{{ url('/')}}/dashboard_assets/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ url('/')}}/dashboard_assets/libs/node-waves/waves.min.js"></script>

    <script src="{{ url('/')}}/dashboard_assets/js/app.js"></script>
    </body>
</html>