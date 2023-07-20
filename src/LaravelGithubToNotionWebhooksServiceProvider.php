<?php

namespace PISpace\LaravelGithubToNotionWebhooks;

use Illuminate\Support\Facades\App;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubIssue;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequest;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequestReview;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\GitHubIssueInterface;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\GitHubPullRequestInterface;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\GitHubPullRequestReviewInterface;
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

        app()->bind(GitHubIssueInterface::class, GitHubIssue::class);
        app()->bind(GitHubPullRequestInterface::class, GitHubPullRequest::class);
        app()->bind(GitHubPullRequestReviewInterface::class, GitHubPullRequestReview::class);

    }
}
