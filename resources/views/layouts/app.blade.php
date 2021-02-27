<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mercado de Batata</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-toggle.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/all.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/_all-skins.min.css') }}">

    <!-- iCheck -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/_all.css') }}">

    <!-- Ionicons -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/ionicons.min.css') }}">

    <!-- Datetimepicker v4.7.14 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}">

    <!-- Select2 v4.0.12-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css') }}">

    <!-- Select2-Bootstrap Theme v4.0.3-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2-bootstrap.min.css') }}">

    <!-- Custom Css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

    <!-- Datepicker Range -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />

    @yield('css')
</head>

<body class="skin-blue sidebar-mini">
{{-- @if (!Auth::guest()) --}}
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="#" class="logo">
                <b>Mercado de Batata</b>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                @php( $user_photo = \Auth::user()->getPhotoAttribute() )
                                <img src="{{ $user_photo }}"
                                     class="user-image" alt="User Image"/>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{!! \Auth::user()->name !!}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    @php( $user_photo = \Auth::user()->getPhotoAttribute() )
                                    <img src="{{ $user_photo }}"
                                         class="img-circle" alt="User Image"/>
                                    <p>
                                        {!! \Auth::user()->name !!}
                                        <small>Membro desde {!! \Auth::user()->created_at->format('M. Y') !!}</small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{!! route('users.show', \Auth::user()->id) !!}" class="btn btn-default btn-flat">Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{!! url('/logout') !!}" class="btn btn-default btn-flat"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Sair
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer" style="max-height: 100px;text-align: center">
            <strong>Copyright © {{date('Y')}} <a href="#">Company</a>.</strong> Todos os direitos reservados.
        </footer>

    </div>
{{-- @else
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{!! url('/') !!}">
                    Mercado de Batata
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{!! url('/home') !!}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <li><a href="{!! url('/login') !!}">Login</a></li>
                    <li><a href="{!! url('/register') !!}">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @endif --}}

    <!-- jQuery 3.1.1 -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-toggle.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>

    <! -- iCheck v1.0.2 -- !>
    <script src="{{ asset('js/icheck.min.js') }}"></script>

    <!-- LoadingOverlay v2.1.6 -->
    <script src="{{ asset('js/loadingoverlay.min.js') }}"></script>

    <!-- Moment v2.15.1 -->
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment-ptbr.js') }}"></script>

    <!-- Datetimepicker v4.7.14 -->
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- Inputmask v4.0.3-b1 -->
    <script src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>

    <!-- Custom masks and datetimepicker patterns -->
    <script src="{{ asset('js/custom-masks.js') }}"></script>

    <!-- Select2 v4.0.12-->
    <script src="{{ asset('js/select2.min.js') }}"></script>

    <!-- Daterange Picker -->
    <script src="{{ asset('js/daterangepicker.min.js') }}"></script>

    <script>

        $.fn.select2.defaults.set( "theme", "bootstrap" );
        $.fn.select2.defaults.set( "width", "100%" );

        function formatMoney(value){
            return parseFloat(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2, style: 'currency', currency: 'BRL' });
        }
        // Loading overlay
        function showLoading() {
            $.LoadingOverlay("show", {
                // image            : '',
                imageColor       : '#ccc',
                // text             : customText,
                // textResizeFactor : 0.2,
                // textColor        : '#fff',
                background       : 'rgba(0, 0, 0, 0.5)',
                fade             : [200, 200],
            });
        }
        function hideLoading() {
            $.LoadingOverlay("hide");
        }

        $('.first-disabled').each(function(){
            $(this).find("option:first").attr('disabled', true);
        });

        // Tooltip
        $(document).tooltip({
            selector: '[data-toggle="tooltip"]',
            trigger: 'hover'
        });

        // Bind mask events on load for edit pages, otherwise removeMaskOnSubmit won't trigger if user doesn't focus on input
        @if(Request::is('*edit*'))
            $(document).ready(function() {
                $(".float-mask").focusWithoutScrolling();
                $(".decimal-mask").focusWithoutScrolling();
                $(".double-mask").focusWithoutScrolling();
                $(".money-mask").focusWithoutScrolling();
                $(".percentage-mask").focusWithoutScrolling();
                $(".integer-mask").focusWithoutScrolling();
                $(".zero-to-ten-mask").focusWithoutScrolling();
                $(".latitude-mask").focusWithoutScrolling();
                $(".longitude-mask").focusWithoutScrolling();
                $(".document-mask").focusWithoutScrolling();
                $(".national-id-mask").focusWithoutScrolling();
                $(".phone-mask").focusWithoutScrolling();
                $(".date-mask").focusWithoutScrolling();
                $(".time-mask").focusWithoutScrolling();
                $(".time-with-seconds-mask").focusWithoutScrolling();
                $(".datetime-mask").focusWithoutScrolling();
                $(".vehicle-plate-mask").focusWithoutScrolling();
                $(".zipcode-mask").focusWithoutScrolling();
                $(".state-mask").focusWithoutScrolling();
                $('input').blur();
            });
        @endif
        // Focus without scrolling
        $.fn.focusWithoutScrolling = function(){
            var x = window.scrollX, y = window.scrollY;
            this.focus();
            window.scrollTo(x, y);
            return this;
        };
        // Automatically select text on focus for input fields
        $.fn.once = function (events, callback) {
            return this.each(function () {
                var myCallback = function (e) {
                    callback.call(this, e);
                    $(this).off(events, myCallback);
                };
                $(this).on(events, myCallback);
            });
        };
        $("input[type='text']").on("focus", function () {
            $(this).once("click keyup", function(e){
                $(this).select();
            });
        });

    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
