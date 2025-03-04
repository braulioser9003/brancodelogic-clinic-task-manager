<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Log In | Minton - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link href="{{ asset('assets/css/default/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
    <!-- Jquery Toast css -->
    <link href="{{asset('assets/libs/jquery-toast-plugin/jquery.toast.min.css')}}" rel="stylesheet" type="text/css" />
</head>
<body class="loading">
<div class="account-pages mt-5 mb-5">
    @yield('content')
</div>
<footer class="footer footer-alt">
    <script>document.write(new Date().getFullYear())</script> &copy; Developer by <a href="http://brancodelogic.com">BranCodeLogic</a>
</footer>

<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<!-- Tost-->
<script src="{{asset('assets/libs/jquery-toast-plugin/jquery.toast.min.js')}}"></script>

<!-- toastr init js-->
<script src="{{asset('/js/pages/toastr.init.js')}}"></script>

<!-- App js -->
<script src="{{asset('/js/app.js')}}"></script>
@include('components.alert')
@yield('script')
</body>
</html>
