<?php

namespace App\Providers;

use App\Models\OrganizationUser;
use App\Observers\OrganizationUserObserver;
use App\Policies\OrganizationUserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        OrganizationUser::observe(OrganizationUserObserver::class);

        // Policies
        Gate::policy(OrganizationUser::class, OrganizationUserPolicy::class);
    }
}
