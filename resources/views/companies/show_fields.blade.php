<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $company->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', \Lang::get('attributes.name')) !!}
    <p>{!! $company->name !!}</p>
</div>

<!-- Cnpj Field -->
<div class="form-group">
    {!! Form::label('cnpj', \Lang::get('attributes.cnpj')) !!}
    <p>{!! $company->cnpj !!}</p>
</div>

<!-- Phone Field -->
<div class="form-group">
    {!! Form::label('phone', \Lang::get('attributes.phone')) !!}
    <p>{!! $company->phone !!}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', \Lang::get('attributes.email')) !!}
    <p>{!! $company->email !!}</p>
</div>

<!-- Photo Field -->
<div class="form-group">
    {!! Form::label('photo', \Lang::get('attributes.photo')) !!}
    {{-- <p>{!! url(asset($company->photo)) !!}</p> --}}
    @if($company->photo)
        <br>
        <img src="{{$company->photo}}" alt="{{$company->photo}}" width="150" height="150">
    @endif
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', \Lang::get('attributes.created_at')) !!}
    <p>{!! $company->readable_updated_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', \Lang::get('attributes.updated_at')) !!}
    <p>{!! $company->readable_updated_at !!}</p>
</div>

