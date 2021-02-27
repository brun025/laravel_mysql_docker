<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Flash;
use Response;
use App\Http\Requests;
use App\Models\Client;
use App\Models\Company;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Supplier;
use App\Models\StatusUser;
use App\Exports\UsersExport;
use App\DataTables\UserDataTable;
use Spatie\Permission\Models\Role;
use App\Repositories\UserRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\AppBaseController;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(UserDataTable $userDataTable)
    {
        return $userDataTable->render('users.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        if (Auth::user()->hasRole(['super_admin'])) {
            $status_users = StatusUser::orderBy('id','asc')->pluck('name', 'id')->all();
            $roles = Role::get()->sortBy('display_name')->pluck('display_name', 'id')->toArray();
            return view('users.create', compact('status_users', 'roles'));
        }

        $roles = Role::whereNotIn('id', [config('enums.roles.SUPER_ADMIN.id')])->get()->sortBy('display_name')->pluck('display_name', 'id')->toArray();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        try{
            $user = $this->userRepository->create($input);

            $user->assignRole($input['role_id']);
    
            Flash::success(\Lang::choice('tables.users','s').' '.\Lang::choice('flash.saved','m'));
        }catch(\Exception $e){
            // dd($e->getMessage());
            Flash::error(\Lang::choice('flash.duplicate_foreign_key','m'));
        }

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->find(request()->user_id);

        if (empty($user)) {
            Flash::error(\Lang::choice('tables.users','s').' '.\Lang::choice('flash.not_found','m'));

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->find(request()->user_id);

        if (empty($user)) {
            Flash::error(\Lang::choice('tables.users','s').' '.\Lang::choice('flash.not_found','m'));

            return redirect(route('users.index'));
        }

        if (Auth::user()->hasRole('super_admin')) {
            $status_users = StatusUser::orderBy('id','asc')->pluck('name', 'id')->all();
            $roles = Role::get()->sortBy('display_name')->pluck('display_name', 'id')->toArray();

            return view('users.edit', compact('status_users', 'roles'))->with('user', $user);
        }

        $roles = Role::whereNotIn('id', [config('enums.roles.SUPER_ADMIN.id')])->get()->sortBy('display_name')->pluck('display_name', 'id')->toArray();

        return view('users.edit', compact('roles'))->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->find(request()->user_id);

        if (empty($user)) {
            Flash::error(\Lang::choice('tables.users','s').' '.\Lang::choice('flash.not_found','m'));
            return redirect(route('users.index'));
        }

        $input = $request->all();

        if($user->cpf == $input['cpf'])
            unset($input['cpf']);

        if($request['keep_password']!=1)
            $input['password'] = bcrypt($input['password']);
        else
            unset($input['password']);

        try{
            if(isset($user->roles[0])){
                $roleId = $user->roles[0]->id;
    
                if($roleId != $input['role_id']){
                    DB::table('model_has_roles')->where('model_id', $user->id)->update([
                        'role_id' => $input['role_id']
                    ]);
                }
            }
            
            $user = $this->userRepository->update($input, $user->id);
            Flash::success(\Lang::choice('tables.users','s').' '.\Lang::choice('flash.updated','m'));
        }catch(\Exception $e){
            // dd($e->getMessage());
            Flash::error(\Lang::choice('flash.duplicate_foreign_key','m'));
        }

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find(request()->user_id);

        if (empty($user)) {
            Flash::error(\Lang::choice('tables.users','s').' '.\Lang::choice('flash.not_found','m'));

            return redirect(route('users.index'));
        }

        $this->userRepository->delete(request()->user_id);

        Flash::success(\Lang::choice('tables.users','s').' '.\Lang::choice('flash.deleted','m'));

        return redirect(route('users.index'));
    }

    public function export()
    {
        return Excel::download(new UsersExport(), 'Usuários.xlsx');
    }
}
