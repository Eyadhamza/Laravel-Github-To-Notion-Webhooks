<?php

// config for PISpace/LaravelGithubToNotionWebhooks
return [
    'github' => [
        'secret' => env('GITHUB_WEBHOOK_SECRET'),
        'events' => [
            'issue' => true,
            'pull_request' => true,
        ],
    ],
    'notion' => [
        'databases' => [
            'issues' => 'cc66f47f03cb4b62a774f6d5f34463ce',
            'users' => '430cedc2495348df926ca520e1255182',
            'repositories' => 'f1370df9fa6d44fc89f23e893d605e09',
            'pull-requests' => 'b99a0a1287d142bbbd7bec87fe1e6406',
        ],
    ]
];
