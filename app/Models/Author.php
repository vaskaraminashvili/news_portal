<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'deleted_at' => 'timestamp',
    ];
}
