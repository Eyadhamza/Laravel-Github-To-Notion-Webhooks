<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Interfaces;

use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubContribution;

interface ContributionTransformerInterface
{
    public static function transform(GithubContribution $contribution = null): array;
}
