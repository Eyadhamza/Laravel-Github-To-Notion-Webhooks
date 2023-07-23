<?php

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
            'pull-requests' => 'b99a0a1287d142bbbd7bec87fe1e6406',
            'users' => '430cedc2495348df926ca520e1255182',
        ],
    ]
];
