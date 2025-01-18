<?php

use App\Models\User;
return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],
    //guard
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],//add the organizer guard
        'organizer' => [
            'driver' => 'session',
            'provider' => 'organizer',
        ],
        'attende' => [
            'driver' => 'session',
            'provider' => 'attende',
        ],

    ],
    //provider
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],
        'admin' => [
            'driver' => 'eloquent',
            'model' =>User::class,
        ],
        'organizer' => [
            'driver' => 'eloquent',
            'model' =>User::class,
        ],
        'attende' => [
            'driver' => 'eloquent',
            'model' =>User::class,
        ],

        // // Add the organizer provider
        // 'organizers' => [
        //     'driver' => 'eloquent',
        //     'model' => App\Models\Organizer::class,
        // ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        // Optional: Add password reset for organizers
        'organizers' => [
            'provider' => 'organizers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
