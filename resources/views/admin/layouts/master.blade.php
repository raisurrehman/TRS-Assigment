<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? '' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet"
    href="{{ asset(env('ADMIN_THEME')) }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/plugins/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/dist/css/custom.css">
  <link rel="stylesheet" href="{{ asset(env('ADMIN_THEME')) }}/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li>
      </ul>

      <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                class="fas fa-sign-out-alt"></i> Logout</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
              {{ csrf_field() }}
            </form>
            <div class="dropdown-divider"></div>
            <a href="{{ route('profile') }}" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                Profile
              </a>
          </div>
        </li>

      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
        <img src="{{ asset(env('ADMIN_THEME')) }}/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">The Right Software</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{ asset(env('ADMIN_THEME')) }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2"
              alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">{{auth()->user()->name}}</a>
          </div>
        </div>

        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item has-treeview menu-open">
              <a href="{{ route('dashboard') }}" class="nav-link {{ $menu_active == 'dashboard' ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      @yield('content')
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.0.1-pre
      </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/jquery/jquery.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/jquery-ui/jquery-ui.min.js"></script>
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/chart.js/Chart.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/sparklines/sparkline.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/jquery-knob/jquery.knob.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/moment/moment.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/select2/js/select2.full.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/daterangepicker/daterangepicker.js"></script>
  <script
    src="{{ asset(env('ADMIN_THEME')) }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/summernote/summernote-bs4.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/dist/js/adminlte.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/dist/js/pages/dashboard.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/dist/js/demo.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/toastr/toastr.min.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}//plugins/datatables/jquery.dataTables.js"></script>
  <script src="{{ asset(env('ADMIN_THEME')) }}/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>


  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('.select2').select2()

    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    $("input[data-bootstrap-switch]").each(function () {
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    $('.toastrDefaultSuccess').click(function () {
      toastr.success('')
    });

    $('.toastrDefaultError').click(function () {
      toastr.error('')
    });

    @if (session() -> has('success'))
      toastr.success('{{ session()->get('success') }}')
    @endif
    @if (session() -> has('error'))
      toastr.error('{{ session()->get('error') }}')
    @endif

  </script>

  @stack('scripts')
</body>

</html>
