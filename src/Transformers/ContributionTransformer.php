<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Transformers;

use Pi\Notion\Core\Properties\NotionMultiSelect;
use Pi\Notion\Core\Properties\NotionPeople;
use Pi\Notion\Core\Properties\NotionSelect;
use Pi\Notion\Core\Properties\NotionText;
use Pi\Notion\Core\Properties\NotionTitle;
use Pi\Notion\Core\Properties\NotionUrl;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubEntity;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\TransformerInterface;

class ContributionTransformer implements TransformerInterface
{

    public static function transform(GithubEntity $entity): array
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
