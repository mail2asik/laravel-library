<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.site_name') }} - {{ $page_title or "Dashboard" }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ secure_asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ secure_asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset("/bower_components/admin-lte/dist/css/skins/skin-blue-light.min.css")}}" rel="stylesheet" type="text/css" />
    <!-- Ladda spinner -->
    <link href="{{ secure_asset("/bower_components/ladda/dist/ladda.min.css")}}" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ secure_asset("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.css")}}">
    <style>
        @media screen and (max-width: 768px) {
            .overflowAutoSmallScreen {
                overflow: auto;
            }
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery 2.1.4 -->
    <script src="{{ secure_asset("/bower_components/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
</head>
<body class="skin-blue-light">
<div class="wrapper">

    <!-- Header -->
    @include('admin.sections.header')

    <!-- Sidebar -->
    @include('admin.sections.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{ $page_title or "Page Title" }}
                <small>{{ $page_description or null }}</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Footer -->
    @include('admin.sections.footer')

</div><!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- Bootstrap 3.3.2 JS -->
<script src="{{ secure_asset("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
<!-- jQuery validation v1.14.0 -->
<script src="{{ secure_asset("/js/jquery.validate.min.js") }}"></script>
<!-- jQuery validation v1.14.0 additional methods -->
<script src="{{ secure_asset("/js/additional-methods.min.js") }}"></script>
<!-- admin-lte App -->
<script src="{{ secure_asset("/bower_components/admin-lte/dist/js/app.min.js") }}" type="text/javascript"></script>
<!-- Ladda spinner -->
<script src="{{ secure_asset("/bower_components/ladda/dist/spin.min.js") }}"></script>
<script src="{{ secure_asset("/bower_components/ladda/dist/ladda.min.js") }}"></script>

@yield('external')

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience -->
</body>
</html>