<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => 'mailer.hdfilme.tv',
        'secret' => 'key-7ac23121cbc56a2a56a76ea01860d8bb',
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => 'User',
        'secret' => '',
    ],

    'facebook' => [
        'client_id' => env("FACEBOOK_CLIENT_ID", '155814578217682'),
        'client_secret' => env("FACEBOOK_CLIENT_SECRET", 'a03dc0d60722d71b56c75500084ddcd5'),
        'redirect' => env("FACEBOOK_REDIRECT_URL", "https://hdfilme.tv/social/handle/facebook"),
    ],
    "google" => [
        "client_id" => env("GOOGLE_CLIENT_ID", "1090229936631-tor28jrf5eleti1a5iaeh7u6s59noduq.apps.googleusercontent.com"),
        "client_secret" => env("GOOGLE_CLIENT_SECRET", "0nqn9FlVPVpTjRA8sf-ovHrh"),
        "redirect" => env("GOOGLE_REDIRECT_URL", "http://hdfilme.dev/social/handle/google")
    ]
    
    
];