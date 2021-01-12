<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::before(function ($user) {
            if ($user->role_id === config('role.super_admin')) {
                return true;
            }
        });

        Gate::define('admin-role', function ($user) {
            if ($user->role_id === config('role.super_admin')) {
                return true;
            }
        });
        
        if (!$this->app->runningInConsole()) {
            $permissions = Permission::all();
            foreach ($permissions as $pms) {
                Gate::define($pms->name, function ($user) use ($pms) {
                    return $user->hasPermission($pms);
                });
            }
        }
    }
}
