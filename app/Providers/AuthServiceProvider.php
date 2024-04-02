<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {

            // return true;
            if($user->hasRole('Super Admin')){
                if(!currentYear()->is_active) {
                    if(Str::contains($ability, 'view') || Str::contains($ability, 'report')){
                        // dump($ability);
                        return true;
                    }
                    return null;
                }else{
                    return true;
                }
            }else if(!currentYear()->is_active) {
                if(Str::contains($ability, 'view')){
                    // dump($ability);
                    return true;
                }
                return null;
            }else{
                return true;
            }
            // return $user->hasRole('Super Admin') ? true : null;
        });

    }
}
