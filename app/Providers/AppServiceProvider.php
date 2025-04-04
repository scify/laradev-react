<?php

namespace App\Providers;

use App\Enums\RolesEnum;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        Vite::prefetch(concurrency: 3);

        $this->configureSecureUrls();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole(RolesEnum::ADMINISTRATOR) ? true : null;
        });

        // Uncomment the following line to enable strict mode for Eloquent models
        /**
         * Behind the scenes, here's what the shouldBeStrict method does:
         *
         * static::preventLazyLoading($shouldBeStrict);
         * static::preventSilentlyDiscardingAttributes($shouldBeStrict);
         * static::preventAccessingMissingAttributes($shouldBeStrict);
         *
         * The preventLazyLoading method will throw an error if you attempt to read model relationships without having eager loaded them.
         * The preventSilentlyDiscardingAttributes method will throw an error if you attempt to set an attribute that doesn't exist on the model.
         * The preventAccessingMissingAttributes method will throw an error if you attempt to read an attribute that doesn't exist on the model.
         */
        // Model::shouldBeStrict();

        // Uncomment the following line to disable mass assignment protection for Eloquent models.
        /**
         * This will allow you to set any attribute on a model using the fill method,
         * without having to define a $fillable or $guarded property on the model.
         */
        // Model::unguard();

        // Uncomment the following line to enable the prohibitDestructiveCommands method for the DB facade.
        /**
         * This will prevent destructive commands (like DROP TABLE) from being executed in production.
         */
        // DB::prohibitDestructiveCommands(app()->isProduction());

        // Uncomment the following line to enable the use of CarbonImmutable for date attributes.
        /**
         * This will make all date attributes return instances of CarbonImmutable instead of Carbon.
         * This is useful if you want to prevent date attributes from being modified accidentally.
         *
         * By default, Laravel uses Carbon for date attributes.
         * This means that when you modify a date attribute, the original model will be modified as well.
         * Example:
         * $date = now();
         * $future = $date->addHours(2); // $date will be modified as well
         *
         * If you use CarbonImmutable, the original model will not be modified.
         * Example:
         * $date = now();
         * $future = $date->addHours(2); // $date will not be modified
         */
        // Date::use(CarbonImmutable::class);
    }

    protected function configureSecureUrls(): void {
        // Determine if HTTPS should be enforced
        $enforceHttps = $this->app->environment(['production', 'staging'])
            && !$this->app->runningUnitTests();

        // Force HTTPS for all generated URLs
        URL::forceHttps($enforceHttps);

        // Ensure proper server variable is set
        if ($enforceHttps) {
            $this->app['request']->server->set('HTTPS', 'on');
        }

        /**
         * The security headers are added in the AddSecurityHeaders middleware
         *
         * @see app/Http/Middleware/AddSecurityHeaders.php
         * @see bootstrap/app.php
         */
    }
}
