{!! Form::open(['route' => ['users.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('users.show', $id) }}" class='btn btn-info btn-xs'>
        <i class="fa fa-info-circle"></i>
    </a>
    <a href="{{ route('users.edit', $id) }}" class='btn btn-warning btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirm('Confirmar exclusão?')"
    ]) !!}
</div>
{!! Form::close() !!}
