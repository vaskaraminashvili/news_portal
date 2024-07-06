<?php

namespace App\Enums;

enum NewsPlace: int
{
    case DEFAULT = 1;
    case BANNER = 2;
    case TOP = 3;
    case HOT = 4;

        public function label(): string
    {
        return match($this) {
            self::DEFAULT => 'Default',
            self::BANNER => 'Banner',
            self::TOP => 'Top',
            self::HOT => 'Hot',
        };
    }
}
