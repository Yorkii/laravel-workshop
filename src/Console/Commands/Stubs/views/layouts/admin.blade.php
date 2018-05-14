<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin panel</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="{{ asset('/vendor/bootstrap/css/bootstrap.min.css') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('/vendor/font-awesome/css/font-awesome.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{ asset('/vendor/ionicons/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/vendor/admin-lte/plugins/iCheck/all.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('/vendor/admin-lte/css/adminlte.min.css') }}">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="{{ asset('/vendor/admin-lte/css/skins/_all-skins.min.css') }}">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        <script>
            function waitFor(t,i){var n=function(t,i){return void 0!==t[i[0]]&&(!(i.length>1)||n(t[i[0]],i.slice(1)))},o=!1;if("string"==typeof t&&(o=!n(window,t.split("."))),"object"==typeof t)for(var e=0;e<t.length;e++)n(window,t[e].split("."))||(o=!0);if(o)return setTimeout(function(){waitFor(t,i)},10);"function"==typeof i&&i()}
        </script>
    </head>
    <body class="hold-transition skin-black sidebar-mini">
        @widget('AdminHeader')
        @widget('AdminSidebar')

        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- jQuery 3 -->
        <script src="{{ asset('/vendor/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{ asset('/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- Slimscroll -->
        <script src="{{ asset('/vendor/jquery/jquery.slimscroll.min.js') }}"></script>
        <!-- FastClick -->
        <script src="{{ asset('/vendor/fastclick/fastclick.js') }}"></script>
        <script src="{{ asset('/vendor/admin-lte/plugins/icheck/icheck.min.js') }}"></script>
        <script src="{{ asset('/vendor/ckeditor/ckeditor.js') }}"></script>

        <script src="{{ asset('/vendor/flot/jquery.flot.js') }}"></script>
        <script src="{{ asset('/vendor/flot/jquery.flot.resize.js') }}"></script>

        <!-- AdminLTE App -->
        <script src="{{ asset('/vendor/admin-lte/js/adminlte.min.js') }}"></script>
    </body>
</html>