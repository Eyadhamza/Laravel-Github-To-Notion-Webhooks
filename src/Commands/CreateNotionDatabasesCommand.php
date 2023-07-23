<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Commands;

use Illuminate\Console\Command;
use Pi\Notion\Core\Models\NotionDatabase;
use Pi\Notion\Core\Properties\NotionDatabaseTitle;
use Pi\Notion\Core\Properties\NotionPeople;
use Pi\Notion\Core\Properties\NotionText;
use Pi\Notion\Core\Properties\NotionTitle;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\IssueTransformerInterface;
use PISpace\LaravelGithubToNotionWebhooks\Transformers\PullRequestTransformer;

class CreateNotionDatabasesCommand extends Command
{
    protected $signature = 'create:notion-databases {parentPageId}';

    protected $description = 'Create Notion databases';

    public function handle(): void
    {
        $parentPageId = $this->argument('parentPageId');

        $this->info('Creating Notion databases...');

        $issueDatabase = NotionDatabase::make()
            ->setParentPageId($parentPageId)
            ->setTitle(NotionDatabaseTitle::make('Issues'))
            ->buildProperties(app(IssueTransformerInterface::class)->transform())
            ->create();

        $this->info('Notion issues databases is created, put the id in your config file: ' . $issueDatabase->getId());

        $pullRequestDatabase = NotionDatabase::make()
            ->setParentPageId($parentPageId)
            ->setTitle(NotionDatabaseTitle::make('Pull Requests'))
            ->buildProperties(app(PullRequestTransformer::class)->transform())
            ->create();

        $this->info('Notion pull requests databases is created, put the id in your config file: ' . $pullRequestDatabase->getId());

        $usersDatabase = NotionDatabase::make()
            ->setParentPageId($parentPageId)
            ->setTitle(NotionDatabaseTitle::make('Users'))
            ->buildProperties([
                'id' => NotionTitle::make('GitHub Username'),
                'profile' => NotionPeople::make('Person'),
            ])
            ->create();

        $this->info('Notion users databases is created, put the id in your config file: ' . $usersDatabase->getId());

        $this->info('Notion databases are created!');
    }
}
