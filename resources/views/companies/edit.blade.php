@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">{{\Lang::choice('tables.companies', 's')}}</h3>
            </div>

            <div class="box-body">
               <div class="row">
                   {!! Form::model($company, ['route' => ['companies.update', $company->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('companies.fields')

                   {!! Form::close() !!}
               </div>
            </div>
        </div>
    </div>
@endsection
