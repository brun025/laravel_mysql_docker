<?php

namespace App\Exports;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;

class CompaniesExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct()
    {
        
    }

    public function array(): array
    {
        $user = \Auth::user();

        if($user->hasRole('super_admin'))
            $companies = Company::all();
        else{
            $companies = [];
            array_push($companies, $user->company);
        }

        $data = array();
        $data[0][0] = "Nome";
        $data[0][1] = "CNPJ";
        $data[0][3] = "Telefone";
        $data[0][2] = "E-mail";

        foreach($companies as $key => $s){
            $indice = 0;
            $data[$key + 1][$indice++] = $s->name;
            $data[$key + 1][$indice++] = $s->cnpj;
            $data[$key + 1][$indice++] = $s->phone;
            $data[$key + 1][$indice++] = $s->email;
        }

        return $data;
    }
}
