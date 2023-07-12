<?php

use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    withoutExceptionHandling();

    $this->createPayload = json_decode(file_get_contents(__DIR__ . '/Payloads/create_issue_webhook.json'), true);
    $this->updatePayload = json_decode(file_get_contents(__DIR__ . '/Payloads/update_issue_webhook.json'), true);
});


it('tests issue handler create', function () {
    $response = $this->withHeaders([
        'X-Hub-Signature-256' => 'sha256=' . hash_hmac('sha256', json_encode($this->createPayload), config('github-webhooks.github.secret')),
    ])->postJson('/github/webhooks', $this->createPayload);
    dd($response->json());
    $response->assertStatus(200);
});


it('tests issue handler update', function () {

    $response = $this->withHeaders([
        'X-Hub-Signature-256' => 'sha256=' . hash_hmac('sha256', json_encode($this->updatePayload), config('github-webhooks.github.secret')),
    ])->postJson('/github/webhooks', $this->updatePayload);

    $response->assertStatus(200);
});
