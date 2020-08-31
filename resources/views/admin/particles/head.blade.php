<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Pixels Project - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Marwa Mahmoud" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('/')}}/dashboard_assets/images/favicon.ico">
    <!-- CSRF TOKEN FOR AJAX -->
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <!-- Bootstrap Css -->
    <link href="{{ url('/')}}/dashboard_assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    
    <!-- DataTables -->
    <link href="{{ url('/')}}/dashboard_assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/')}}/dashboard_assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ url('/')}}/dashboard_assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />     
    <!-- Select CSS -->
    <link href="{{ url('/')}}/dashboard_assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/')}}/dashboard_assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="{{ url('/')}}/dashboard_assets/libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="{{ url('/')}}/dashboard_assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="{{ url('/')}}/dashboard_assets/libs/select2/css/select2.min.css" rel="stylesheet" />

    @yield('styles')
    
    <!-- Icons Css -->
    <link href="{{ url('/')}}/dashboard_assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ url('/')}}/dashboard_assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>