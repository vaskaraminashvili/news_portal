<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'active' => 'boolean',
        'parent_id' => 'integer',
    ];

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
