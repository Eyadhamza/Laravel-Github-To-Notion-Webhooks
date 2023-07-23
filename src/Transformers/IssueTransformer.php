<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Transformers;

use Pi\Notion\Core\Properties\NotionPeople;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubIssue;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequest;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\IssueTransformerInterface;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\PullRequestTransformerInterface;

class IssueTransformer implements IssueTransformerInterface
{
    public static function transform(GithubIssue $issue = null): array
    {
        return ContributionTransformer::transform($issue);
    }

}
