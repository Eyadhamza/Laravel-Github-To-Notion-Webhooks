<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Interfaces;

use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubIssue;

interface IssueTransformerInterface
{
    public static function transform(GithubIssue $issue = null): array;
}
