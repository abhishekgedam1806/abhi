<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();

        $this->mapAdminRoutes();
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $path = base_path('routes/web.php');
        if (file_exists($path)) {
            Route::middleware('web')
                    ->namespace($this->namespace)
                    ->group($path);
        }
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        $path = base_path('routes/admin.php');
        if (file_exists($path)) {
            Route::prefix('admin')
                    ->middleware(['web', 'admin', 'auth:admin', 'checkAdminRoles'])
                    ->namespace($this->namespace)
                    ->group($path);
        }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $path = base_path('routes/api.php');
        if (file_exists($path)) {
            Route::prefix('api')
                    ->middleware('api')
                    ->namespace($this->namespace)
                    ->group($path);
        }
    }

}
