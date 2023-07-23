<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Interfaces;

use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequest;

interface PullRequestTransformerInterface
{
    public static function transform(GithubPullRequest $pullRequest = null): array;
}
