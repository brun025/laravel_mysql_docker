<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;

class UsersExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct()
    {
        
    }

    public function array(): array
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
            )->get();
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
            )->get();
        }

        $data = array();
        $data[0][0] = "Nome";
        $data[0][1] = "Perfil";
        $data[0][2] = "E-mail";
        $data[0][3] = "Telefone";

        foreach($users as $key => $s){
            $indice = 0;
            $data[$key + 1][$indice++] = $s->name;
            $data[$key + 1][$indice++] = $s->role_display_name;
            $data[$key + 1][$indice++] = $s->email;
            $data[$key + 1][$indice++] = $s->phone;
        }

        return $data;
    }
}
