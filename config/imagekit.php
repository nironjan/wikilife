<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ImageKit config
    |--------------------------------------------------------------------------
    |
    | This file provides a top-level config key `imagekit` so older parts of
    | the app can call `config('imagekit.url_endpoint')`. Values are sourced
    | from environment variables or from `services.imagekit` if present.
    |
    */

    'public_key' => env('IMAGEKIT_PUBLIC_KEY', config('services.imagekit.public_key')),
    'private_key' => env('IMAGEKIT_PRIVATE_KEY', config('services.imagekit.private_key')),
    'url_endpoint' => env('IMAGEKIT_URL_ENDPOINT', config('services.imagekit.url_endpoint')),

];

