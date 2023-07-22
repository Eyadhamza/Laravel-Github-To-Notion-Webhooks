<?php
namespace PISpace\LaravelGithubToNotionWebhooks\Enum;

enum PullRequestActionTypeEnum: string
{
    case ASSIGNED = 'assigned';
    case AUTO_MERGE_DISABLED = 'auto_merge_disabled';
    case AUTO_MERGE_ENABLED = 'auto_merge_enabled';
    case CLOSED = 'closed';
    case CONVERTED_TO_DRAFT = 'converted_to_draft';
    case DEMILESTONED = 'demilestoned';
    case DEQUEUED = 'dequeued';
    case EDITED = 'edited';
    case ENQUEUED = 'enqueued';
    case LABELED = 'labeled';
    case LOCKED = 'locked';
    case MILESTONED = 'milestoned';
    case OPENED = 'opened';
    case READY_FOR_REVIEW = 'ready_for_review';
    case REOPENED = 'reopened';
    case REVIEW_REQUEST_REMOVED = 'review_request_removed';
    case REVIEW_REQUESTED = 'review_requested';
    case SYNCHRONIZE = 'synchronize';
    case UNASSIGNED = 'unassigned';
    case UNLABELED = 'unlabeled';
    case UNLOCKED = 'unlocked';
}
