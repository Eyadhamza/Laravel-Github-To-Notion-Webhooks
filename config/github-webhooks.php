<?php

// config for PISpace/LaravelGithubToNotionWebhooks
return [
    'secret' => env('GITHUB_WEBHOOK_SECRET'),
    'events' => [
        'issue' => true,
    ],
];
