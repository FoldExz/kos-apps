<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}"> <title>@yield('title') &mdash; Si-Kos</title>

  <link rel="stylesheet" href="{{ asset('stisla/node_modules/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/@fortawesome/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/ionicons201/css/ionicons.min.css') }}"> 

  <link rel="stylesheet" href="{{ asset('stisla/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/assets/css/components.css') }}">

  {{-- Custom CSS --}}
  <link rel="stylesheet" href="{{ asset('stisla/assets/css/custom.css') }}">
  
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/chart.js/dist/Chart.min.css') }}"> 

    <link rel="stylesheet" href="{{ asset('stisla/assets/select2/css/select2.min.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

  @stack('css') </head>

<body>
  <div id="app">
    <div class="main-wrapper">

      @include('layouts.navbar')

      <div class="main-sidebar sidebar-style-2">
        @include('layouts.sidebar')
      </div>

      <div class="main-content">
        @yield('content')
      </div>

      @include('layouts.footer')

    </div>
  </div>

  <script src="{{ asset('stisla/node_modules/jquery/dist/jquery.min.js') }}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="{{ asset('stisla/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('stisla/assets/select2/js/select2.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script src="{{ asset('stisla/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

  <script src="{{ asset('stisla/assets/js/stisla.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/scripts.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/custom.js') }}"></script>

  <script src="{{ asset('stisla/assets/js/page/bootstrap-modal.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/page/components-table.js') }}"></script>

    <script src="{{ asset('stisla/assets/js/sweetalert2.all.min.js') }}"></script>

<script src="{{ asset('stisla/modules/chart.min.js') }}"></script>
<script src="{{ asset('stisla/node_modules/chart.js/dist/Chart.min.js') }}"></script>
{{-- <script src="{{ asset('stisla/js/page/dist/modules-chartjs.js') }}"></script> --}}
{{-- <script src="{{ asset('stisla/assets/js/page/modules-chartjs.js') }}"></script> --}}

  <script>
    $.ajaxSetup({
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

  @stack('js') @yield('scripts')
</body>
</html>