<?php

namespace PISpace\LaravelGithubToNotionWebhooks;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use PISpace\LaravelGithubToNotionWebhooks\Commands\LaravelGithubToNotionWebhooksCommand;

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
            ->hasCommand(LaravelGithubToNotionWebhooksCommand::class);
    }
}
