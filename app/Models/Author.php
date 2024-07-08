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

    public function getInitialsAttribute()
    {
        $firstName = $this->attributes['name'];
        $lastName = $this->attributes['surname'];

        // Get the first character of each name and convert them to uppercase
        return strtoupper(substr($firstName, 0, 1) .'.'. substr($lastName, 0, 1));
    }
}
