<?php

use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    withoutExceptionHandling();

    $this->payload = json_decode(file_get_contents(__DIR__ . '/Payloads/issue_webhook.json'), true);
});


it('tests handler', function () {
//    dd(env('NOTION_TOKEN'));
    $response = $this->withHeaders([
        'X-Hub-Signature-256' => 'sha256=' . hash_hmac('sha256', json_encode($this->payload), config('github-webhooks.github.secret')),
    ])->postJson('/github/webhook', $this->payload);

    dd($response->json());
    $response->assertStatus(200);
});
