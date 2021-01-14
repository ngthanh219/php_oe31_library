<?php

namespace App\Http\Middleware;

use App\Models\Request;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()) {
            $statusRequest = [
                config('request.reject'), 
                config('request.return'), 
                config('request.late'), 
                config('request.forget')
            ];
            $user = User::findOrFail(Auth::id())->load(['requests' => function ($query) use($statusRequest) {
                $query->whereNotIn('status', $statusRequest);
            }]);
            $status = $user->status;
            foreach ($user->requests as $item) {
                $returnDate = Carbon::parse($item->return_date);
                $today = Carbon::today();
                $date = $returnDate->lt($today);
                if ($date) {
                    if ($item->status === config('request.pending') || $item->status == config('request.accept')) {
                        $req = Request::findOrFail($item->id)->load('user');
                        foreach ($req->books as $book) {
                            $book->update([
                                'in_stock' => $book->in_stock + config('book.book'),
                            ]);
                        }
                        $user->update([
                            'status' => $status + config('user.add_status'),
                        ]);
                        $req->update([
                            'status' => config('request.forget'),
                        ]);
                    } else {
                        $req = Request::findOrFail($item->id)->load('user');
                        $user->update([
                            'status' => $status + config('user.add_status'),
                        ]);
                        $req->update([
                            'status' => config('request.late'),
                        ]);
                    }
                }
            }
        }
        if (Auth::user()->role_id == config('role.client')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
