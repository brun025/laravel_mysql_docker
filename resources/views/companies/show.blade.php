@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">{{\Lang::choice('tables.companies', 's')}}</h3>
            </div>
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('companies.show_fields')
                    <a href="{!! route('companies.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
