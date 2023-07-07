<?php

namespace PISpace\LaravelGithubToNotionWebhooks;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use PISpace\LaravelGithubToNotionWebhooks\Commands\LaravelGithubToNotionWebhooksCommand;

class LaravelGithubToNotionWebhooksServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-github-to-notion-webhooks')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-github-to-notion-webhooks_table')
            ->hasCommand(LaravelGithubToNotionWebhooksCommand::class);
    }
}
