<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Shop :: Administrative Panel</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('admin-assets/plugins/dropzone/dropzone.css') }}"> --}}
    {{-- dropzone style  --}}
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/summernote/summernote.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/select2/css/select2.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-assets/css/datetimepicker.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <style>
        <style>.img-flag {
            width: 20px;
            height: 15px;
            margin-right: 10px;
        }
    </style>
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include('admin.layouts.header')
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        @include('admin.layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            @yield('content')

        </div>
        <!-- /.content-wrapper -->
        @include('admin.layouts.footer')

    </div>
    <!-- ./wrapper -->
    <!-- jQuery -->
    <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('admin-assets/js/demo.js') }}"></script>
    {{-- DropZone script  --}}
    <script src="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('admin-assets/plugins/summernote/summernote.min.js') }}"></script>

    <!-- Select 2 -->
    <script src="{{ asset('admin-assets/plugins/select2/js/select2.js') }}"></script>

    {{-- datatime js --}}

    <script src="{{ asset('admin-assets/js/datetimepicker.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
            }
        });

        $(document).ready(function() {
            $('.summernote').summernote({
                height: 250
            });
        });
    </script>

    @yield('customJs')

</body>

</html>
