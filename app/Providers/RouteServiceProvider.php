<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        $this->mapAuthRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        $this->mapAccessTokenRoutes();

        $this->mapIntegrateRoutes();
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
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "auth" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAuthRoutes()
    {
        Route::prefix('api')
            ->middleware('auth')
            ->namespace($this->namespace)
            ->group(base_path('routes/auth.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::prefix('api/v1/admin')
            ->middleware('admin')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "access token" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAccessTokenRoutes()
    {
        Route::prefix('access/tokens')
            ->middleware('accessToken')
            ->namespace($this->namespace)
            ->group(base_path('routes/accessToken.php'));
    }

    /**
     * Define the "integrate" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapIntegrateRoutes()
    {
        Route::prefix('integrate')
            ->middleware('integrate')
            ->namespace($this->namespace)
            ->group(base_path('routes/integrate.php'));
    }
}
