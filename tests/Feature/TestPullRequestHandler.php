<?php

use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    withoutExceptionHandling();

    $this->createPayload = json_decode(file_get_contents(__DIR__ . '/Payloads/PullRequest/create_pull_request_webhook.json'), true);
    $this->updatePayload = json_decode(file_get_contents(__DIR__ . '/Payloads/PullRequest/update_pull_request_webhook.json'), true);

});


it('tests pull request handler create', function () {
    $response = $this->withHeaders([
        'X-Hub-Signature-256' => 'sha256=' . hash_hmac('sha256', json_encode($this->createPayload), config('github-webhooks.github.secret')),
    ])->postJson('/github/webhooks', $this->createPayload);

    $response->assertStatus(200);
});


it('tests pull request handler update', function () {
    $response = $this->withHeaders([
        'X-Hub-Signature-256' => 'sha256=' . hash_hmac('sha256', json_encode($this->updatePayload), config('github-webhooks.github.secret')),
    ])->postJson('/github/webhooks', $this->updatePayload);

    $response->assertStatus(200);
});
