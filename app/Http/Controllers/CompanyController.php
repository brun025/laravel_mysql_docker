<?php

namespace App\Http\Controllers;

use Flash;
use Response;
use App\Models\Stock;
use App\Http\Requests;
use App\DataTables\CompanyDataTable;
use App\Repositories\CompanyRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Exports\CompaniesExport;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends AppBaseController
{
    /** @var  CompanyRepository */
    private $companyRepository;

    public function __construct(CompanyRepository $companyRepo)
    {
        $this->companyRepository = $companyRepo;
    }

    /**
     * Display a listing of the Company.
     *
     * @param CompanyDataTable $companyDataTable
     * @return Response
     */
    public function index(CompanyDataTable $companyDataTable)
    {
        return $companyDataTable->render('companies.index');
    }

    /**
     * Show the form for creating a new Company.
     *
     * @return Response
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created Company in storage.
     *
     * @param CreateCompanyRequest $request
     *
     * @return Response
     */
    public function store(CreateCompanyRequest $request)
    {
        $input = $request->all();

        $company = $this->companyRepository->create($input);

        $company->save();

        Flash::success(\Lang::choice('tables.companies','s').' '.\Lang::choice('flash.saved','f'));

        return redirect(route('companies.index'));
    }

    /**
     * Display the specified Company.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $company = $this->companyRepository->find($id);

        if (empty($company)) {
            Flash::error(\Lang::choice('tables.companies','s').' '.\Lang::choice('flash.not_found','f'));

            return redirect(route('companies.index'));
        }

        return view('companies.show')->with('company', $company);
    }

    /**
     * Show the form for editing the specified Company.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $company = $this->companyRepository->find($id);

        if (empty($company)) {
            Flash::error(\Lang::choice('tables.companies','s').' '.\Lang::choice('flash.not_found','f'));

            return redirect(route('companies.index'));
        }

        return view('companies.edit')->with('company', $company);
    }

    /**
     * Update the specified Company in storage.
     *
     * @param  int              $id
     * @param UpdateCompanyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCompanyRequest $request)
    {
        $company = $this->companyRepository->find($id);

        if (empty($company)) {
            Flash::error(\Lang::choice('tables.companies','s').' '.\Lang::choice('flash.not_found','f'));

            return redirect(route('companies.index'));
        }

        $company = $this->companyRepository->update($request->all(), $id);

        $company->save();

        Flash::success(\Lang::choice('tables.companies','s').' '.\Lang::choice('flash.updated','f'));

        return redirect(route('companies.index'));
    }

    /**
     * Remove the specified Company from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $company = $this->companyRepository->find($id);

        if (empty($company)) {
            Flash::error(\Lang::choice('tables.companies','s').' '.\Lang::choice('flash.not_found','f'));

            return redirect(route('companies.index'));
        }

        $this->companyRepository->delete($id);

        Flash::success(\Lang::choice('tables.companies','s').' '.\Lang::choice('flash.deleted','f'));

        return redirect(route('companies.index'));
    }

    public function export()
    {
        return Excel::download(new CompaniesExport(), 'Empresas.xlsx');
    }
}
