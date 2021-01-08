<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = User::with('role')->orderBy('id', 'desc')->get();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(UserRequest $request)
    {
        $data = $request->all();
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            $data['password'] = bcrypt($data['password']);
            User::create($data);
            $request->session()->flash('infoMessage', trans('user.create_user_success'));

            return redirect()->route('admin.users.index');
        } else {
            $request->session()->flash('checkIssetEmail', trans('user.isset_email'));

            return redirect()->route('admin.users.index');
        }
    }

    public function edit($id)
    {
        $user = User::find($id);
        if ($user) {
            return view('admin.user.edit', compact('user'));
        } else {
            session()->flash('infoMessage', trans('user.isset_id'));

            return redirect()->route('admin.users.index');
        }
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->update([
                'name' => $request->name,
                'adrress' => $request->address,
                'phone' => $request->phone,
            ]);
            session()->flash('infoMessage', trans('user.update_user_success'));

            return redirect()->route('admin.users.index');
        } else {
            session()->flash('infoMessage', trans('user.isset_id'));

            return redirect()->route('admin.users.index');
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            session()->flash('infoMessage', trans('user.delete_user_success'));

            return redirect()->route('admin.users.index');
        } else {
            session()->flash('infoMessage', trans('user.isset_id'));

            return redirect()->route('admin.users.index');
        }
    }

    public function search(Request $request)
    {
        $users = User::where('name', 'LIKE', '%' . $request->key . '%')->orderBy('id', 'DESC')->get();

        return view('admin.user.search', compact('users'));
    }
}
