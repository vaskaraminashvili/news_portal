<?php

namespace App\Enums\News;

enum NewsLayout: String
{
    case DEFAULT = 'default';
    case LARGE = 'large';
    case SMALL = 'small';

    public static function icons(): array
    {
        return [
            self::DEFAULT->value => 'heroicon-m-newspaper',
            self::LARGE->value => 'heroicon-m-rectangle-stack',
            self::SMALL->value => 'heroicon-m-squares-2x2',
        ];
    }

}
