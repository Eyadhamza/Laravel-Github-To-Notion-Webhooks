<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Enum;

enum GithubEventTypeEnum: string
{
    case ISSUE = 'issue';
    case PULL_REQUEST = 'pull_request';
    case PULL_REQUEST_REVIEW = 'pull_request_review';
}
