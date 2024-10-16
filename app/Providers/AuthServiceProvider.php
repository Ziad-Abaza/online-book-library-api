<?php

namespace App\Providers;


use App\Models\User;
use App\Models\Role;
use App\Models\CategoryGroup;
use App\Models\BookSeries;
use App\Policies\UserPolicy;
use App\Policies\RolePolicy;
use App\Policies\CategoryPolicy;
use App\Policies\BookSeriesPolicy;
use App\Models\AuthorRequest;
use App\Policies\AuthorRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        CategoryGroup::class => CategoryPolicy::class,
        BookSeries::class => BookSeriesPolicy::class,
        AuthorRequest::class => AuthorRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
