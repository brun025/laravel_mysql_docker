{!! Form::open(['route' => ['companies.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('companies.show', $id) }}" class='btn btn-info btn-xs'>
        <i class="fa fa-info-circle"></i>
    </a>
    <a href="{{ route('companies.edit', $id) }}" class='btn btn-warning btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirm('Confirmar exclusão?')"
    ]) !!}
</div>
{!! Form::close() !!}
