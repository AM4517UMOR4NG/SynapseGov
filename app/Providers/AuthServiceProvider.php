<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Report::class => \App\Policies\ReportPolicy::class,
        \App\Models\Complaint::class => \App\Policies\ComplaintPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register FilePolicy for Gate authorization
        Gate::define('view', function ($user, $reportable) {
            if ($reportable instanceof \App\Models\Report) {
                return app(\App\Policies\ReportPolicy::class)->view($user, $reportable);
            }

            if ($reportable instanceof \App\Models\Complaint) {
                return app(\App\Policies\ComplaintPolicy::class)->view($user, $reportable);
            }

            return app(\App\Policies\FilePolicy::class)->view($user, $reportable);
        });

        Gate::define('download', function ($user, $reportable) {
            return app(\App\Policies\FilePolicy::class)->download($user, $reportable);
        });

        Gate::define('preview', function ($user, $reportable) {
            return app(\App\Policies\FilePolicy::class)->preview($user, $reportable);
        });
    }
}
