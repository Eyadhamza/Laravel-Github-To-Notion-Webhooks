<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Transformers;

use Pi\Notion\Core\Properties\NotionPeople;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequest;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\PullRequestTransformerInterface;

class PullRequestTransformer implements PullRequestTransformerInterface
{
    public static function transform(GithubPullRequest $pullRequest): array
    {
        return array_merge(ContributionTransformer::transform($pullRequest), [
            'reviewers' => NotionPeople::make('Reviewers'),
        ]);
    }

}
