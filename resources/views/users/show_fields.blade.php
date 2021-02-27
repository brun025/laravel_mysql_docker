<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $user->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', \Lang::get('attributes.name').':') !!}
    <p>{!! $user->name !!}</p>
</div>

<!-- CPF Field -->
<div class="form-group">
    {!! Form::label('cpf', \Lang::get('attributes.cpf').':') !!}
    <p>{!! $user->cpf !!}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', \Lang::get('attributes.email').':') !!}
    <p>{!! $user->email !!}</p>
</div>

<!-- Password Field -->
{{-- <div class="form-group">
    {!! Form::label('password', \Lang::get('attributes.password').':') !!}
    <p>{!! $user->password !!}</p>
</div> --}}

@isset ($user->phone)
    <!-- Phone Field -->
    <div class="form-group">
        {!! Form::label('phone', \Lang::get('attributes.phone').':') !!}
        <p>{!! $user->phone !!}</p>
    </div>
@endisset

@isset ($user->photo)
    <!-- Photo Field -->
    <div class="form-group">
        {!! Form::label('photo', \Lang::get('attributes.photo').':') !!}
        <div style="width:10%;">
            <a href="{{ $user->photo }}">
                <img id="photo-img" class="thumbnail preview-img" src="{{ $user->photo }}"/>
            </a>
        </div>
    </div>
@endisset

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', \Lang::get('attributes.created_at').':') !!}
    <p>{!! $user->readable_created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', \Lang::get('attributes.updated_at').':') !!}
    <p>{!! $user->readable_updated_at !!}</p>
</div>

