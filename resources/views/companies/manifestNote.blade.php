<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mercado de Batata</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Datetimepicker v4.7.14 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}">

    <!-- Select2 v4.0.12-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css">

    <!-- Select2-Bootstrap Theme v4.0.3-->
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap.min.css') }}">

    <!-- Custom Css-->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Datepicker Range -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    @yield('css')

    <style>
        .table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
            border-top: none;
        }
        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
            border-top: none;
        }
        .table>thead>tr>th {
            border-bottom: none;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 3px;
        }
    </style>
</head>

<body class="skin-blue sidebar-mini">
{{-- @if (!Auth::guest()) --}}
<div class="content">
    @include('flash::message')
    @include('adminlte-templates::common.errors')

    <div class="row">
        <div class="box filter">
            <div class="box-body">

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 20px">
                        <label>Selecione o intervalo de dias das movimentações:</label>
                        <input class="col-md-12 " type="text" class="datefilter" name="datefilter" value=""/>
                    </div>
                </div>

                Você está visualizando informações referentes ao período de <b>{{$first_day->format('d/m/Y')}}</b> até <b>{{$second_day->format('d/m/Y')}}</b>
            </div>
        </div>
        
        <!-- Detalhes compras -->
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="no-padding dashboard-user-image-wrapper" style="float:left;">
                                <img class="dashboard-user-image" src="{{ $company->photo }}" style="width:95%;height:100%;"/>
                                {{-- <img class="dashboard-user-image" src="http://via.placeholder.com/100x100" style="width:95%;height:100%;"/> --}}
                            </div>
                        </div>
                        <div class="col-md-9 text-center">
                            <h3 class="box-title dashboard-box-title" style="margin-right:6% !important;">JR COMÉRCIO</h3><br>
                            <h5 class="box-title" style="font-size:90% !important;">Avenida Dona Mariquinha, 1620 - Centro - Maria da Fé - MG - 37517-000</h5><br>
                            <h3 class="box-title" style="font-size:90% !important;">Telefones: (35) 3662-1546 / (35) 998654949 /  (35) 998309257 ---- E-mail: jrempreendimentosmg@gmail.com</h3>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h3 class="box-title dashboard-box-title">*** MANIFESTO ***</h3>
                        </div>
                        Data Lacto: {{date('d/m/Y', strtotime(today()))}}
                        <br>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Manifesto</th>
                                        <th>Cliente</th>
                                        <th>Valor Bruto</th>
                                        <th>Líquido</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(collect($manifests)->sortBy('orderDate') as $manifest)
                                        {{-- @if($manifest['netProfit'] >= 0)
                                            <tr style="color:green;">
                                        @else
                                            <tr style="color:red;">
                                        @endif --}}
                                            <td>{{$manifest['date']}}</td>
                                            <td>{{$manifest['manifest']}}</td>
                                            <td>{{$manifest['client']}}</td>
                                            <td>R$ {{ number_format($manifest['grossValue'], 2, ',', '.') }}</td>
                                            <td>R$ {{ number_format($manifest['netProfit'], 2, ',', '.') }}</td>
                                            <td>{{ number_format($manifest['%']) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr>
                        <div class="col-md-6">
                            <strong style="position: absolute;top: 0;">
                                TOTAL BRUTO: R$ {{ number_format($grossValueTotal, 2, ',', '.') }}
                            </strong><br>
                            <strong>
                                TOTAL LÍQUIDO: R$ {{ number_format($netProfitTotal, 2, ',', '.') }}
                            </strong><br>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>

    </div>
</div>
    <!-- jQuery 3.1.1 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

    <!-- Daterange Picker -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Limpar',
                applyLabel:  'Filtrar',
            }
        });

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            var first_day = picker.startDate.format('YYYY-MM-DD');
            var second_day = picker.endDate.format('YYYY-MM-DD');
            var routeDashboard = "{{ route('manifest.note', [request()->company_id]) }}"+"?first_day="+first_day+"?second_day="+second_day;
            window.location = routeDashboard;
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

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

        $(document).bind("keyup keydown", function(e){
            if(e.ctrlKey && e.keyCode == 80){
                $('.dashboard-user-image').css('width', 50)
                $('.filter').hide()
            }
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>