<?php


use Illuminate\Support\Facades\Route;
use PISpace\LaravelGithubToNotionWebhooks\Controllers\GithubWebhookController;

Route::post('github/webhooks', GithubWebhookController::class);
