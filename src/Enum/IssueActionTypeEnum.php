<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Enum;

enum IssueActionTypeEnum: string {
    case ASSIGNED = 'assigned';
    case CLOSED = 'closed';
    case DELETED = 'deleted';
    case DEMILESTONED = 'demilestoned';
    case EDITED = 'edited';
    case LABELED = 'labeled';
    case LOCKED = 'locked';
    case MILESTONED = 'milestoned';
    case OPENED = 'opened';
    case PINNED = 'pinned';
    case REOPENED = 'reopened';
    case TRANSFERRED = 'transferred';
    case UNASSIGNED = 'unassigned';
    case UNLABELED = 'unlabeled';
    case UNLOCKED = 'unlocked';
    case UNPINNED = 'unpinned';
}

