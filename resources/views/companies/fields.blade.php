<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', \Lang::get('attributes.name')) !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Cnpj Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cnpj', \Lang::get('attributes.cnpj')) !!}
    {!! Form::text('cnpj', null, ['class' => 'form-control document-mask']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', \Lang::get('attributes.phone')) !!}
    {!! Form::text('phone', null, ['class' => 'form-control phone-mask']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', \Lang::get('attributes.email')) !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Photo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('photo', \Lang::get('attributes.photo')) !!}
    {!! Form::file('photo', null, ['class' => 'form-control']) !!}
</div>
<div class="clearfix"></div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('companies.index') !!}" class="btn btn-default">Cancelar</a>
</div>
