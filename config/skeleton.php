<?php

return [

    'auth' => [
        'backend_oauth' => (bool)env('USE_BACKEND_OAUTH'),
        'display_permission_in_exception' => false,
        'display_role_in_exception' => false,
        "client_id" => env('PASSWORD_CLIENT_ID'),
        "client_secret" => env('PASSWORD_CLIENT_SECRET'),
        "url" => env('OAUTH_CLIENT_URL'),
    ],
];