<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Services\DataTablesDefaults;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable()
    {
        if(\Auth::user()->hasRole('super_admin')){
            $users = User::select(
                'users.*',
                DB::raw('(
                    select roles.display_name
                    from roles
                    join model_has_roles
                    on model_has_roles.role_id=roles.id
                    where model_has_roles.model_id=users.id
                )as role_display_name')
            );
        }
        else{
            $users = User::where('company_id', \Auth::user()->company_id)->select(
                'users.*',
                DB::raw('(
                    select roles.display_name
                    from roles
                    join model_has_roles
                    on model_has_roles.role_id=roles.id
                    where model_has_roles.model_id=users.id
                )as role_display_name')
            );
        }

        return DataTables::of($users)
            ->filterColumn('role_display_name', function($query, $keyword){
                $query->whereRaw('(
                    select roles.display_name
                    from roles
                    join model_has_roles
                    on model_has_roles.role_id=roles.id
                    where model_has_roles.model_id=users.id
                ) like ?', ["%{$keyword}%"]);
            })
            ->addColumn('action', 'users.datatables_actions')
            ->rawColumns(['action']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '100px', 'printable' => false, 'title' => \Lang::get('datatable.action')])
            ->parameters(DataTablesDefaults::getParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name'                       => ['render' => '(data)? ((data.length>180)? data.substr(0,180)+"..." : data) : "-"', 'title' => \Lang::get('attributes.name')],
            'role_display_name'          => ['render' => '(data)? ((data.length>180)? data.substr(0,180)+"..." : data) : "-"', 'title' => \Lang::get('attributes.role_id')],
            'email'                      => ['render' => '(data)? ((data.length>180)? data.substr(0,180)+"..." : data) : "-"', 'title' => \Lang::get('attributes.email')],
            'phone'                      => ['render' => '(data)? ((data.length>180)? data.substr(0,180)+"..." : data) : "-"', 'title' => \Lang::get('attributes.phone')],
            // 'company_name'               => ['render' => '(data)? ((data.length>180)? data.substr(0,180)+"..." : data) : "-"', 'title' => \Lang::get('attributes.company')],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'usersdatatable_' . time();
    }
}
