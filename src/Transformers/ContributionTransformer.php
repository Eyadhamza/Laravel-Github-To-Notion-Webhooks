<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Transformers;

use Pi\Notion\Core\Properties\NotionMultiSelect;
use Pi\Notion\Core\Properties\NotionPeople;
use Pi\Notion\Core\Properties\NotionSelect;
use Pi\Notion\Core\Properties\NotionText;
use Pi\Notion\Core\Properties\NotionTitle;
use Pi\Notion\Core\Properties\NotionUrl;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubContribution;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubEntity;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\ContributionTransformerInterface;

class ContributionTransformer implements ContributionTransformerInterface
{

    public static function transform(GithubContribution $contribution = null): array
    {
        return [
            'id' => NotionText::make('ID'),
            'title' => NotionTitle::make('Title'),
            'url' => NotionUrl::make('Url'),
            'description' => NotionText::make('Description'),
            'state' => NotionSelect::make('State'),
            'status' => NotionSelect::make('Status'),
            'sender' => NotionPeople::make('Author'),
            'repositoryName' => NotionText::make('Repository'),
            'assignees' => NotionPeople::make('Assignees'),
            'labels' => NotionMultiSelect::make('Labels'),
        ];
    }

}
