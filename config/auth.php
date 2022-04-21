<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Auth User Pendent
    |--------------------------------------------------------------------------
    |
    | This value is used when the user with pendent status try to login in the
    | application. By default users with this status can't login, but you
    | can enable it according to your need.
    |
    */

    'pendent_user' => (bool)env('AUTH_PENDENT_USER', false),

    /*
    |--------------------------------------------------------------------------
    | Default User Role
    |--------------------------------------------------------------------------
    |
    | This value is used on public user register (email or social networks)
    | routes, to set the default application user role. This value must be
    | the id (numeric) of the correspondent role, and by default is role
    | id 1 => admin
    |
    */

    'default_role' => (int)env('AUTH_DEFAULT_ROLE', 1),

    /*
    |--------------------------------------------------------------------------
    | Allowed routes roles access
    |--------------------------------------------------------------------------
    |
    | Must be defined which roles can access a route group.
    |
    */

    'routes_allowed_roles' => [
        'admin' => (array)explode(',', env('ADMIN_ROUTES_ALLOWED_ROLES')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed admin emails domains
    |--------------------------------------------------------------------------
    |
    | Must be defined from which domains can admins roles emails be.
    |
    */

    'allowed_admin_emails_domains' =>
        (array)explode(',', env('ALLOWED_ADMIN_EMAILS_DOMAINS')),


    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

    /*
   |--------------------------------------------------------------------------
   | Recovery Email
   |--------------------------------------------------------------------------
   |
   | The expire time is the number of minutes that the reset email token should be
   | considered valid. This security feature keeps tokens short-lived so
   | they have less time to be guessed. You may change this as needed.
   |
   */

    'recovery_email_timeout' => 10080
];
