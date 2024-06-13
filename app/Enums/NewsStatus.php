<?php

namespace App\Enums;

enum NewsStatus : string
{
    case DRAFT = 'Draft';
    case PUBLISHED = 'Published';
    case ARCHIVED = 'Archived';
    case PENDING = 'Pending';
    case REJECTED = 'Rejected';
}
