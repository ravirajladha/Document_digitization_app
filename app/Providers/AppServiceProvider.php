<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DocumentTableService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DocumentTableService::class, function ($app) {
            // return new instance of service
            return new DocumentTableService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $user = Auth::user();
            // Pass the user to the view
            $view->with('user', $user);
    
            if ($user) {
                // Assuming your User model has a permissions() relationship defined
                $permissions = $user->permissions()->pluck('display_name'); // Use 'name' if you want to use the permission names
                $view->with('permissions', $permissions);
            } else {
                // Make sure to always pass permissions, even if it's an empty collection
                $view->with('permissions', collect());
            }
        });
    }
}
