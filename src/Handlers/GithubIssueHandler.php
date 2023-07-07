<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Handlers;

use PISpace\LaravelGithubToNotionWebhooks\Models\GithubIssue;

class GithubIssueHandler extends BaseGithubHandler
{

    public function handle(): self
    {
        GithubIssue::make($this->request->getEventBody())
            ->saveToNotion();

        return $this;
    }
}
