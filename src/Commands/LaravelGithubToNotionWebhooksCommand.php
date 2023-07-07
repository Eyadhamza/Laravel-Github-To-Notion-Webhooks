<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Commands;

use Illuminate\Console\Command;

class LaravelGithubToNotionWebhooksCommand extends Command
{
    public $signature = 'laravel-github-to-notion-webhooks';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
