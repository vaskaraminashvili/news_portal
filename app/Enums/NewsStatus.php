<?php

namespace App\Enums;

enum NewsStatus : string
{
    case DRAFT = 'Draft';
    case PUBLISHED = 'Published';
    case ARCHIVED = 'Archived';
    case PENDING = 'Pending';
    case REJECTED = 'Rejected';

    public function getColor(): string
    {
        return match ($this) {
            self::DRAFT, self::PENDING =>'warning',
            self::PUBLISHED => 'success',
            self::ARCHIVED => 'info',
            self::REJECTED => 'danger',
        };
    }
}
