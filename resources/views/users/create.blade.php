@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-info box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">{{\Lang::choice('tables.users', 's')}}</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => ['users.store', request()->company_id], 'files' => true]) !!}

                        @include('users.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
