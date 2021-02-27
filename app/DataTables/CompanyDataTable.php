<?php

namespace App\DataTables;

use App\Models\Company;
use App\Services\DataTablesDefaults;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Services\DataTable;

class CompanyDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $user = \Auth::user();

        if($user->hasRole('super_admin'))
            $companies = Company::select();
        else{
            $companies = [];
            array_push($companies, $user->company);
        }

        return DataTables::of($companies)
            ->addColumn('action', 'companies.datatables_actions')
            ->rawColumns(['action', 'name']);
        ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Company $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Company $model)
    {
        return $model->newQuery();
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
            'name'                => ['render' => '(data)? ((data.length>180)? data.substr(0,180)+"..." : data) : "-"', 'title' => \Lang::get('attributes.name')],
            'cnpj'                => ['render' => '(data)? ((data.length>180)? data.substr(0,180)+"..." : data) : "-"', 'title' => \Lang::get('attributes.cnpj')],
            'phone'               => ['render' => '(data)? ((data.length>180)? data.substr(0,180)+"..." : data) : "-"', 'title' => \Lang::get('attributes.phone')],
            'email'               => ['render' => '(data)? ((data.length>180)? data.substr(0,180)+"..." : data) : "-"', 'title' => \Lang::get('attributes.email')],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'companiesdatatable_' . time();
    }
}
