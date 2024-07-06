<?php

namespace App\Models;
use App\Enums\NewsPlace as NewsPlaceEnum;
use Illuminate\Database\Eloquent\Relations\Pivot;

class NewsPlace extends Pivot
{
    protected $table = 'news_place';

    protected $casts = [
        'place_id' => NewsPlaceEnum::class,
    ];

    public function news(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(News::class);
    }
}
