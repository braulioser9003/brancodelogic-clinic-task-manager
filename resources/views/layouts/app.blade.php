<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Starter | Minton - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/default/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet" type="text/css" />

    <!-- Sweet Alert2 css -->
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Data Table -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Jquery Toast css -->
    <link href="{{asset('assets/libs/jquery-toast-plugin/jquery.toast.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Plugins css -->
    <link href="{{asset('assets/libs/mohithg-switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/libs/fullcalendar/core/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/fullcalendar/daygrid/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/fullcalendar/bootstrap/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/fullcalendar/timegrid/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/fullcalendar/list/main.min.css') }}" rel="stylesheet" type="text/css" />
    @yield('styles')
</head>
<body class="loading">
<div id="wrapper">
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        @include('layouts.footer')
    </div>
</div>

<!-- Right Sidebar -->
@include('layouts.rigthbar')
<div class="rightbar-overlay"></div>

<!-- Vendor js -->
<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<!-- Tost-->
<script src="{{asset('assets/libs/jquery-toast-plugin/jquery.toast.min.js')}}"></script>

<!-- Data Table -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<!-- Plugins Js -->
<script src="{{ asset('assets/libs/mohithg-switchery/switchery.min.js') }}"></script>
<script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
<script src="{{ asset('assets/libs/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('assets/libs/jquery.quicksearch/jquery.quicksearch.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>

 <!-- Sweet Alert2 js-->
 <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- Form advanced init js-->

<script src="{{ asset('assets/libs/fullcalendar/core/main.min.js') }}"></script>
<script src="{{ asset('assets/libs/fullcalendar/bootstrap/main.min.js') }}"></script>
<script src="{{ asset('assets/libs/fullcalendar/daygrid/main.min.js') }}"></script>
<script src="{{ asset('assets/libs/fullcalendar/timegrid/main.min.js') }}"></script>
<script src="{{ asset('assets/libs/fullcalendar/list/main.min.js') }}"></script>
<script src="{{ asset('assets/libs/fullcalendar/interaction/main.min.js') }}"></script>

<!-- Calendar init -->
<script src="{{ asset('assets/js/pages/calendar.init.js') }}"></script>

<!-- DataTables init js-->
<script src="{{asset('/js/pages/datatables.init.js')}}"></script>

<!-- toastr init js-->
<script src="{{asset('/js/pages/toastr.init.js')}}"></script>

<!-- init js -->
<script src="{{asset('/js/pages/form-advanced.init.js')}}"></script>

<script src="{{asset('/js/app.js')}}"></script>

<!-- App js -->
<script src="{{asset('/js/tailwind.js')}}"></script>
@include('components.alert')
@yield('script')
</body>
</html>
