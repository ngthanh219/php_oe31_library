<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Permission\PermissionRepositoryInterface;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleRepo, $perRepo;

    public function __construct(RoleRepositoryInterface $roleRepo, PermissionRepositoryInterface $perRepo)
    {
        $this->middleware('superAdmin');
        $this->roleRepo = $roleRepo;
        $this->perRepo = $perRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->roleRepo->getAll();

        return view('admin.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = $this->perRepo->get();

        return view('admin.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $result = $this->roleRepo->create([
            'name' => $request->name,
        ]);

        if ($request->permissions) {
            $this->roleRepo->attach($result, 'permissions', $request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('infoMessage', trans('role.create_role_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permissions = $this->perRepo->get();
        $role = $this->roleRepo->find($id);
        $role = $this->roleRepo->load($role, 'permissions');

        return view('admin.role.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissions = $this->perRepo->get();
        $role = $this->roleRepo->find($id);
        $role = $this->roleRepo->load($role, 'permissions');

        return view('admin.role.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleRepo->find($id);
        $this->roleRepo->sync($role, 'permissions', $request->permission);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = $this->roleRepo->find($id);

        if ($role->permissions) {
            $this->roleRepo->sync($role, 'permissions');
        }
        $result = $this->roleRepo->destroy($id);

        if ($result) {
            return redirect()->route('admin.roles.index')->with('infoMessage',
                trans('message.role_delete_success'));
        }

        return redirect()->route('admin.roles.index')->with('infoMessage',
            trans('message.role_delete_fail'));
    }
}
