<?php

namespace App\Providers;

use App\Models\Tarea;
use App\Models\Team;
use App\Models\User;
use App\Policies\TareaPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Auth\Access\Response;
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
        Team::class => TeamPolicy::class,
        Tarea::class => TareaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('administra', function (User $user, Tarea $tarea) {
            return $tarea->categoria == 'Otra'
                ? Response::allow()
                : Response::deny('You must be an administrator.');
        });

    }
}
