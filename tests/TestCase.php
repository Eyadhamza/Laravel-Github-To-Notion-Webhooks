<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Tests;

use Dotenv\Dotenv;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use PISpace\LaravelGithubToNotionWebhooks\LaravelGithubToNotionWebhooksServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        if (file_exists(dirname(__DIR__) . '/.env.test')) {
            (Dotenv::createImmutable(dirname(__DIR__), '.env.test'))->load();
        }

        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'PISpace\\LaravelGithubToNotionWebhooks\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelGithubToNotionWebhooksServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-github-to-notion-webhooks_table.php.stub';
        $migration->up();
        */
    }
}
