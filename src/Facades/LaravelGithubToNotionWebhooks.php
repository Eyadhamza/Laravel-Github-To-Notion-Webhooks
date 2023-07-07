<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \PISpace\LaravelGithubToNotionWebhooks\LaravelGithubToNotionWebhooks
 */
class LaravelGithubToNotionWebhooks extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \PISpace\LaravelGithubToNotionWebhooks\LaravelGithubToNotionWebhooks::class;
    }
}
