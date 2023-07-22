<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Interfaces;

use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubEntity;

interface TransformerInterface
{
    public static function transform(GithubEntity $entity): array;
}
