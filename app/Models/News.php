<?php

namespace App\Models;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class News extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'publish_date' => 'timestamp',
        'author_id' => 'integer',
    ];

    public static function getForm(): array
    {
        return [
            TextInput::make('title')
                ->required()
                ->maxLength(400),
            TextInput::make('slug')
                ->required()
                ->maxLength(255),
            Textarea::make('short_description')
                ->required()
                ->columnSpanFull(),
            Textarea::make('description')
                ->required()
                ->columnSpanFull(),
            DateTimePicker::make('publish_date'),
            Select::make('author_id')
                ->relationship('author', 'name'),
            TextInput::make('status')
                ->required()
                ->maxLength(255),
            TextInput::make('views')
                ->numeric(),
            TextInput::make('sort')
                ->numeric(),
            TextInput::make('place')
                ->maxLength(255),
        ];

    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
