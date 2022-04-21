<?php

namespace App\Http;

use App\Http\Middleware\AccessTokenAuth;
use App\Http\Middleware\AuthACL;
use App\Http\Middleware\AuthStatusCheck;
use App\Http\Middleware\CheckForMaintenanceMode;
use App\Http\Middleware\CheckLocale;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\IntegrateAuth;
use App\Http\Middleware\ResponseJson;
use App\Http\Middleware\RoutesACL;
use App\Http\Middleware\TelescopeAuth;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustHosts;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\VerifyCsrfToken;
use Fruitcake\Cors\HandleCors;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Tymon\JWTAuth\Http\Middleware\Authenticate;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        TrustHosts::class,
        TrustProxies::class,
        HandleCors::class,
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            'throttle:60,1',
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'auth' => [
            'throttle:120,1',
            ResponseJson::class,
            CheckLocale::class,
            StartSession::class,
            SubstituteBindings::class,
        ],

        'admin' => [
            'throttle:60,1',
            ResponseJson::class,
            CheckLocale::class,
            Authenticate::class,
            AuthStatusCheck::class,
            RoutesACL::class,
            AuthACL::class,
            SubstituteBindings::class,
        ],

        'accessToken' => [
            'throttle:60,1',
            ResponseJson::class,
            CheckLocale::class,
            AccessTokenAuth::class,
            // \App\Http\Middleware\AccessTokenACL::class,
            SubstituteBindings::class,
        ],

        'integrate' => [
            'throttle:60,1',
            ResponseJson::class,
            CheckLocale::class,
            IntegrateAuth::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'bindings' => SubstituteBindings::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'password.confirm' => RequirePassword::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,
        'auth.status' => AuthStatusCheck::class,
        'telescope.auth' => TelescopeAuth::class,
    ];
}
