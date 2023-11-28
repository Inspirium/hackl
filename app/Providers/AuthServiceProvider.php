<?php

namespace App\Providers;

use App\Models\Membership;
use App\Models\News;
use App\Models\Result;
use App\Models\Thread;
use App\Models\User;
use App\Policies\MembershipPolicy;
use App\Policies\NewsPolicy;
use App\Policies\ResultPolicy;
use App\Policies\ThreadPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Thread::class => ThreadPolicy::class,
        Result::class => ResultPolicy::class,
        News::class => NewsPolicy::class,
        Membership::class => MembershipPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
