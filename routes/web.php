<?php


use Illuminate\Support\Facades\Route;
use PISpace\LaravelGithubToNotionWebhooks\Controllers\GithubWebhookController;

Route::prefix('github/webhooks')->group(function () {
    Route::post('', GithubWebhookController::class);
});
