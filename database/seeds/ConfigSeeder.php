<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Roles
        $roles = array_map('addTimestamp', array_values(config('enums.roles')));
        DB::table('roles')->insert($roles);

        // Status Users
        $statusUsers = array_map('addTimestamp', array_values(config('enums.status_users')));
        DB::table('status_users')->insert($statusUsers);

        // // Super admin user
        // DB::table('users')->insert(['name' => 'Super Admin', 'email' => 'super@admin.com', 'password' => bcrypt('123456'), 'phone' => NULL,'status_user_id' => config('enums.status_users.ACTIVE.id'), 'phone' => '', 'cpf' => '1212121', 'photo' => asset('images/default/no_user.png'), 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')]);
        // DB::table('model_has_roles')->insert(['role_id' => config('enums.roles.SUPER_ADMIN.id'), 'model_type' => 'App\Models\User','model_id' => 1]);

        // // Admin user
        // DB::table('users')->insert(['name' => 'Admin', 'email' => 'admin@admin.com', 'password' => bcrypt('123456'), 'phone' => NULL, 'status_user_id' => config('enums.status_users.ACTIVE.id'), 'phone' => '', 'cpf' => '12121222',  'photo' => asset('images/default/no_user.png'), 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')]);
        // DB::table('model_has_roles')->insert(['role_id' => config('enums.roles.ADMIN.id'), 'model_type' => 'App\Models\User','model_id' => 2]);

        // //Company
        // DB::table('companies')->insert(['name' => 'Empresa teste', 'email' => 'empresa.com.br', 'cnpj' => '11.111.111/1111-11', 'phone' => '(99) 99999-9999', 'photo' => asset('images/default/no_logo.png'), 'created_at' => DB::raw('CURRENT_TIMESTAMP'), 'updated_at' => DB::raw('CURRENT_TIMESTAMP')]);

        // DB::table('users')->where('id', \App\Models\User::last()->id)->update([
        //     'company_id' => \App\Models\Company::first()->id
        // ]);
        //Company
        $company = Company::create([
            'name' => 'Empresa teste', 
            'email' => 'empresa.com.br', 
            'cnpj' => '11.111.111/1111-11', 
            'phone' => '(99) 99999-9999', 
            'photo' => asset('images/default/no_logo.png')
        ]);

        // Super admin user
        $super = User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => bcrypt('123456'),
            'status_user_id' => config('enums.status_users.ACTIVE.id'),
            'cpf' => '1212121',
            'photo' => asset('images/default/no_user.png'),
        ]);

        DB::table('model_has_roles')->insert(['role_id' => config('enums.roles.SUPER_ADMIN.id'), 'model_type' => 'App\Models\User','model_id' => $super->id]);

        // Admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'status_user_id' => config('enums.status_users.ACTIVE.id'),
            'cpf' => '1212122',
            'photo' => asset('images/default/no_user.png'),
            'company_id' => $company->id
        ]);
        
        DB::table('model_has_roles')->insert(['role_id' => config('enums.roles.ADMIN.id'), 'model_type' => 'App\Models\User','model_id' => $admin->id]);

    }
}
