<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Handlers;

use PISpace\LaravelGithubToNotionWebhooks\WebhookRequests\GithubWebhookRequest;

abstract class BaseGithubHandler
{
    protected GithubWebhookRequest $request;

    public function __construct(GithubWebhookRequest $request)
    {
        $this->request = $request;
    }
    public static function run(GithubWebhookRequest $request): self
    {
        return (new static($request))->handle();
    }

    abstract public function handle(): self;
}
