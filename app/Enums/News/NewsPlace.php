<?php

namespace App\Enums\News;

enum NewsPlace: string
{
    case DEFAULT = 'default';
    case BANNER = 'Banner';
    case TOP = 'Top';
    case HOT = 'Hot';
}
