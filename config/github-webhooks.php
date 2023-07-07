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
            'issues' => '74dc9419bec24f10bb2e65c1259fc65a',
        ],
    ]
];
