<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Batata | Admin</title>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome-all.css') }}">

    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('css/_all.css') }}">

    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('css/source-sans-pro.css') }}">

    <!-- Theme Style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/_all-skins.min.css') }}">

    <!-- Custom CSS -->
    <style>
        /* Backgound */
        .login-page{
            margin-top:-20px;
            background-color: #e1e1ea;
            padding-left:5px;
            padding-top:10px;
            background-image: url("/images/bg.jpg");
            background-repeat: repeat;
            background-attachment: fixed;
            background-position: center center;
            -webkit-background-size: auto;
            -moz-background-size: auto;
            -o-background-size: auto;
            background-size: auto;
        }

        /* Login logo */
        .logo-img{
            width:200px;
        }
        .login-logo{
            margin-bottom:50px;
        }

        /* Login box */
        .login-box-body{
            border-radius:5px!important;
        }

        /* iCheck */
        .icheck{
            margin-top:6px;
            margin-bottom:12px;
        }

        /* Primary button */
        .btn-primary{
            border-radius:5px!important;
            font-weight:bold;
            text-transform: uppercase;
            letter-spacing:1.5px;
        }



        /* COLOR: Primary button */
        .btn-primary {
            background-color: #BA0007;
            border-color:  #810000;
            color: white !important;
        }
        .btn-primary:hover {
            background-color: #810000;
            border-color: #BA0007;
        }
        .btn-primary:active {
            background-color: #810000 !important;
            border-color:  #BA0007 !important;
        }
        .btn-primary:focus {
            background-color: #810000 !important;
            border-color:  #BA0007 !important;
        }

        /* COLOR: Links */
        a {
            color: #BA0007;
        }
        a:hover {
            color: #810000;
        }
        a:focus {
            color: #810000;
        }

        /* COLOR: Form higlight color */
        .form-control:focus {
            border-color: #BA0007;
            box-shadow: none;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            {{-- <img class="logo-img" src="/images/sites/inovai.png" alt="INOVAi"/> --}}
        </div>

        <div class="login-box-body">
            @include('flash::message')
            @include('adminlte-templates::common.errors')

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>

    <!-- iCheck -->
    <script src="{{ asset('js/icheck.min.js') }}"></script>

    <!-- Custom Scripts -->
    <script type="text/javascript">
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        });
    </script>
</body>
</html>
