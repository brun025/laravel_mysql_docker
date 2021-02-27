@extends('layouts.app')

@section('content')
    <div class="content">
        {{-- @if(Auth::user()->hasRole('manager') && Auth::user()->manager->feedback_email=="change@me.com")
            <ul class="alert alert-warning" style="list-style-type:none;">
                <li>É necessário alterar o"<b>E-mail para Feedback dos Usuários</b>" para receber os e-mails enviados via "<b>Fale Conosco</b>" no App. Clique <a href="{!! route('managers.edit', Auth::user()->manager->id) !!}"><b>aqui</b></a> ou no ícone <i class="fas fa-pencil-alt" style="font-size:11px; margin-right:1px"></i> logo abaixo para editar essa informação.</li>
            </ul>
        @endif --}}
        @include('flash::message')
        @include('adminlte-templates::common.errors')

        <div class="row">
            <!-- Boas Vindas -->
            <div class="col-md-12">
                <div class="box box-primary no-border" style="border-radius:5px">
                    <div class="box-body">
                        <!-- Check current user photo -->
                        @php( $user_photo = \Auth::user()->getPhotoAttribute() )

                        <div class="col-sm-2 no-padding dashboard-user-image-wrapper">
                            <img id="photo-img" class="dashboard-user-image pull-left" src="{{ $user_photo }}" style="width:200px;height:200px;"/>
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-sm-10 content-header content-header-default no-padding">
                            <h1 class="pull-left" style="margin-bottom:10px;">
                                Bem-vindo (a), <b>{{\Auth::user()->name}}</b>!
                                {{-- @if(Auth::user()->hasRole('manager'))
                                    <a href="{!! route('managers.edit', Auth::user()->manager->id) !!}">
                                        <i class="fas fa-pencil-alt dasboard-edit-pencil"></i>
                                    </a>
                                @else --}}
                                    <a href="{!! route('users.edit', \Auth::user()->id) !!}">
                                        <i class="fas fa-pencil-alt dasboard-edit-pencil"></i>
                                    </a>
                                {{-- @endif --}}
                            </h1>
                            <div class="clearfix"></div>

                            <div class="form-group">
                                <div class="col-md-12 no-padding" style="margin-bottom:10px">
                                    Aqui você pode ver os últimos usuários e empresas que se cadastraram no sistema, assim como o total de cada um.<br/>
                                    Também é possível ver o total de pontos concedidos, sorteios realizados e trocas efetuadas.<br/>
                                    Para acessar as demais funcionalidades do sistema, utilize o menu ao lado.
                                </div>
                                {{-- @if(Auth::user()->hasRole('manager'))
                                    <div class="col-md-6 no-padding">
                                        <p style="margin-bottom:3px"><b>{{ \Lang::get('attributes.points_conversion_rate') }}: </b>{{ Auth::user()->manager->points_conversion_rate }}</p>
                                        <p style="margin-bottom:3px"><b>{{ \Lang::get('attributes.coupon_conversion_rate') }}: </b>{{ Auth::user()->manager->coupon_conversion_rate }}</p>
                                    </div>
                                @endif --}}
                                <div class="col-md-6 no-padding">
                                    <p style="margin-bottom:3px"><b>Email: </b>{{ \Auth::user()->email }}</p>
                                    {{-- @if(Auth::user()->hasRole('manager'))
                                        <p style="margin-bottom:3px"><b>{{ \Lang::get('attributes.feedback_email') }}: </b>{{ Auth::user()->manager->feedback_email }}</p>
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados Totais -->
            {{-- @if(Auth::user()->hasRole('manager'))
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border dashboard-box-header">
                            <h3 class="box-title dashboard-box-title">Dados Totais</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Total de Pontos Concedidos aos Clientes</th>
                                            <th>Total de Pontos Concedidos à {{Auth::user()->name}}</th>
                                            <th>Total de Trocas Confirmadas</th>
                                            <th>Total de Sorteio de Pontos Realizados</th>
                                            <th>Total de Sorteio de Cupons Realizados</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $customer_points }}</td>
                                            <td>{{ $manager_points }}</td>
                                            <td>{{ $exchanges_total }}</td>
                                            <td>{{ $involuntary_draws_total }}</td>
                                            <td>{{ $draws_total }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            @endif --}}

            <!-- Últimos Clientes -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border dashboard-box-header">
                        <h3 class="box-title dashboard-box-title">Últimos Usuários</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>

                    @if(count($recent_users)>0)
                        <div class="box-body" style="padding-bottom:0">
                            Há <b>{{$users_active_total + $users_inactive_total}}</b> usuários cadastrados no sistema, dos quais <b>{{$users_active_total}}</b> estão ativos e <b>{{$users_inactive_total}}</b> estão inativos.<br/>
                            Os últimos cadastros realizados foram:
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Cadastrado Em</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_users as $customer)
                                            <tr>
                                                {{-- <td><a href="{{ route('customers.dashboard', $customer->id) }}">{{$customer->user->name}}</a></td> --}}
                                                <td><a href="">{{$customer->name}}</a></td>
                                                <td>{{ $customer->email}}</td>
                                                <td>{{$customer->created_at->format('d/m/Y H:i')}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="box-body">
                            Ainda não há clientes cadastrados.
                        </div>
                    @endif

                    @if(count($recent_users)>0)
                        <div class="box-footer clearfix">
                            <a href="{!! route('users.index') !!}" class="btn btn-default pull-right">Ver Todos</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Últimas Empresas -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border dashboard-box-header">
                        <h3 class="box-title dashboard-box-title">Últimas Empresas</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>

                    @if(count($recent_companies)>0)
                        <div class="box-body" style="padding-bottom:0">
                            Há <b>{{$companies}}</b> empresas cadastradas no sistema.<br/>
                            Os últimos cadastros realizados foram:
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Cadastrada Em</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_companies as $company)
                                            <tr>
                                                {{-- <td><a href="{{ route('companies.dashboard', $company->id) }}">{{$company->fantasy_name}}</a></td> --}}
                                                <td><a href="">{{$company->name}}</a></td>
                                                <td>{{ $company->email }}</td>
                                                <td>{{$company->created_at->format('d/m/Y H:i')}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="box-body">
                            Ainda não há empresas cadastradas.
                        </div>
                    @endif

                    @if(count($recent_companies)>0)
                        <div class="box-footer clearfix">
                            <a href="{!! route('companies.index') !!}" class="btn btn-default pull-right">Ver Todas</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var objDiv = document.getElementById("chat-box");
            objDiv.scrollTop = objDiv.scrollHeight;
        });
    </script>
@endsection
