<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8" />
    <title>TopWork - Việc làm hàng đầu</title>
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}" /> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('admin/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('admin/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('admin/css/app.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <link href="{{ asset('admin/vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"
      id="app-style" />

    <link rel="stylesheet" href="{{ asset('assets/user/trumbowyg/trumbowyg.min.css') }}" />
  </head>

  <body>
    <!-- Begin page -->
    <div class="wrapper">
      <!-- Sidenav Menu Start -->
      @include('admin.body.sidebar')
      <!-- Sidenav Menu End -->
      <!-- Topbar Start -->
      @include('admin.body.header')
      <!-- Topbar End -->
      <div class="page-content">
        <div class="page-container">
          @yield('content')
        </div>
      </div>
    </div>
    <script src="{{ asset('admin/js/jquery-3.6.0.min.js') }}"></script>
    <script>
      // $.ajaxSetup({
      //   headers: {
      //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //   }
      // });
    </script>
    <script src="{{ asset('admin/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('admin/js/app.js') }}"></script>
    <script src="{{ asset('admin/js/extended-sweetalerts.js') }}"></script>
    {{-- <script src="{{ asset('admin/vendor/bootstrap/bootstrap.js') }}"></script> --}}

    <!-- Apex Chart js -->
    <script src="{{ asset('admin/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/user/trumbowyg/trumbowyg.min.js') }}"></script>
    <script>
      $('.textarea').trumbowyg({
        btns: [
          ['viewHTML'],
          ['undo', 'redo'],
          ['formatting'],
          ['strong', 'em', 'del'],
          ['superscript', 'subscript'],
          ['link'],
          ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
          ['unorderedList', 'orderedList'],
          ['horizontalRule'],
          ['removeformat'],
          ['fullscreen']
        ]
      });
    </script>
    @yield('customJs')
  </body>

</html>
