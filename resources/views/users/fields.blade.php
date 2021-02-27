<!-- Role Field -->
<div class="form-group col-sm-12">
    {!! Form::label('role_id', \Lang::get('attributes.role_id').':') !!}
    {!! Form::select('role_id', ['' => 'Selecionar'] + $roles, isset($user) ? $user->roles->first()->id : null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', \Lang::get('attributes.name').':') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- CPF Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cpf', \Lang::get('attributes.cpf').':') !!}
    {!! Form::text('cpf', null, ['class' => 'form-control document-mask']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', \Lang::get('attributes.email').':') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

@isset($status_users)
    <!-- Status User Id Field -->
    <div class="form-group col-md-6">
        {!! Form::label('status_user_id', \Lang::get('attributes.status').':') !!}
        {!! Form::select('status_user_id', ['' => 'Selecionar'] + $status_users, null, ['class' => 'form-control']) !!}
    </div>
@else
    {!! Form::hidden('status_user_id', 1, ['class' => 'form-control']) !!}
@endisset

<!-- Edit behaviour -->
@if(isset($user))
    <!-- Keep Password -->
    <div class="form-group col-md-12" style="margin-top:0; margin-bottom:10px">
        <label class="checkbox checkbox-inline no-margin">
            {!! Form::checkbox('keep_password', 1, 1, ['class' => 'field', 'id' => 'keep_password', 'onChange' => 'valueChanged()']) !!}
            <p style="margin-top:3px; margin-left:5px; margin-bottom:0; font-weight:bold"> {{Lang::get('attributes.keep_password')}} </p>
        </label>
    </div>

    <!-- Hide if keep password is selected -->
    <div class="hide-field" style="display:none">
        <!-- Password Field -->
        <div class="form-group col-md-6">
            {!! Form::label('password', \Lang::get('attributes.password').':') !!}
            <input type="password" class="form-control" name="password">
        </div>

        <!-- Password Confirmation Field -->
        <div class="form-group col-md-6">
            {!! Form::label('password', \Lang::get('attributes.password_confirmation').':') !!}
            <input type="password" name="password_confirmation" class="form-control">
        </div>
    </div>

<!-- Create behaviour -->
@else
    <!-- Password Field -->
    <div class="form-group col-md-6">
        {!! Form::label('password', \Lang::get('attributes.password').':') !!}
        <input type="password" class="form-control" name="password">
    </div>

    <!-- Password Confirmation Field -->
    <div class="form-group col-md-6">
        {!! Form::label('password', \Lang::get('attributes.password_confirmation').':') !!}
        <input type="password" name="password_confirmation" class="form-control">
    </div>
@endif

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', \Lang::get('attributes.phone').':') !!}
    {!! Form::text('phone', null, ['class' => 'form-control phone-mask']) !!}
</div>

<!-- Photo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('photo', \Lang::get('attributes.photo').':') !!}
    {!! Form::file('photo', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancelar</a>
</div>

<!-- Scripts -->
@section('scripts')
    <script type="text/javascript">
        // Keep password
        function valueChanged() {
            if ($('#keep_password').is(':checked')) {
                $(".hide-field").hide();
            } else {
                $(".hide-field").show();
            }
        }
        document.getElementById("keep_password").checked = true;
    </script>
@endsection
