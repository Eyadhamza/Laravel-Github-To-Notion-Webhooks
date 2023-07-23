<?php

namespace PISpace\LaravelGithubToNotionWebhooks;

use PISpace\LaravelGithubToNotionWebhooks\Commands\CreateNotionDatabasesCommand;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\ContributionTransformerInterface;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\IssueTransformerInterface;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\PullRequestTransformerInterface;
use PISpace\LaravelGithubToNotionWebhooks\Transformers\ContributionTransformer;
use PISpace\LaravelGithubToNotionWebhooks\Transformers\IssueTransformer;
use PISpace\LaravelGithubToNotionWebhooks\Transformers\PullRequestTransformer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelGithubToNotionWebhooksServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->mergeConfigFrom(__DIR__.'/../config/notion-api-wrapper.php', 'notion-api-wrapper');


        $package
            ->name('laravel-github-to-notion-webhooks')
            ->hasConfigFile('github-webhooks')
            ->hasViews()
            ->hasMigration('create_laravel-github-to-notion-webhooks_table')
            ->hasCommand(CreateNotionDatabasesCommand::class);

        $this->app->bind(IssueTransformerInterface::class, IssueTransformer::class);
        $this->app->bind(ContributionTransformerInterface::class, ContributionTransformer::class);
        $this->app->bind(PullRequestTransformerInterface::class, PullRequestTransformer::class);

    }
}
