<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

class UserController extends Controller
{

    public function index()
    {
        if (Auth::user()->can('user.index')) {
            $users = User::with('role')->orderBy('id', 'desc')->paginate(config('pagination.limit'));

            return view('admin.user.index', compact('users'));
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    public function create()
    {
        if (Auth::user()->can('user.create')) {
            return view('admin.user.create');
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    public function store(UserRequest $request)
    {
        if (Auth::user()->can('user.store')) {
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
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('user.edit')) {
            $user = User::find($id);
            if ($user) {
                return view('admin.user.edit', compact('user'));
            } else {
                session()->flash('infoMessage', trans('user.isset_id'));

                return redirect()->route('admin.users.index');
            }
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    public function update(UserRequest $request, $id)
    {
        if (Auth::user()->can('user.update')) {
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
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('user.destroy')) {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                session()->flash('infoMessage', trans('user.delete_user_success'));

                return redirect()->route('admin.users.index');
            } else {
                session()->flash('infoMessage', trans('user.isset_id'));

                return redirect()->route('admin.users.index');
            }
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    public function search(Request $request)
    {
        if (Auth::user()->can('user.search')) {
            $users = User::where('name', 'LIKE', '%' . $request->key . '%')->orderBy('id', 'DESC')->get();

            return view('admin.user.search', compact('users'));
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
