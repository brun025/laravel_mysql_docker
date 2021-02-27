@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">{{\Lang::choice('tables.companies', 'p')}}</h3>
                <div class="box-tools pull-right">
                    <a class="btn-box-tool btn btn-success text-white" style="text-transform: uppercase; padding-right: 10px" href="{!! route('companies.export') !!}" target="_blank">Exportar</a>
                    <a class="btn-box-tool btn btn-success text-white" style="text-transform: uppercase; padding-right: 10px" href="{!! route('companies.create') !!}"><i class="fa fa-plus" style="padding: 0 10px"></i>Adicionar nova</a>
                </div>
            </div>
            <div class="box-body">
                @include('companies.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

