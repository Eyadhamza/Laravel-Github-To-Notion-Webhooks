<?php

// config for PISpace/LaravelGithubToNotionWebhooks
return [
    'github' => [
        'secret' => env('GITHUB_WEBHOOK_SECRET'),
        'events' => [
            'issue' => true,
        ],
    ],
    'notion' => [
        'databases' => [
            'issues' => 'cc66f47f03cb4b62a774f6d5f34463ce',
        ],
    ]
];
