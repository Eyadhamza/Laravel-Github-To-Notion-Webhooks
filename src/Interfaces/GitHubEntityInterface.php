<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Interfaces;

use PISpace\LaravelGithubToNotionWebhooks\Enum\IssueActionTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Requests\GithubWebhook;

interface GitHubEntityInterface
{
    public function mapToNotion(): array;
    public function getAttributes();
    public function setAction(string $action): self;
    public function getAction();
    public function setAttributes(array $data): self;
    public function setNotionDatabaseId(): self;

    public static function fromRequest(GithubWebhook $request): static;
}
