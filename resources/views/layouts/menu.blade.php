<li class="{{ Request::is('companies*') ? 'active' : '' }}">
    <a href="{!! route('companies.index') !!}"><i class="fa fa-building"></i><span>{!! \Lang::choice('tables.companies','p') !!}</span></a>
</li>

<li class="{{ Request::is('*users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-users"></i><span>{!! \Lang::choice('tables.users','p') !!}</span></a>
</li>