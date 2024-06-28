<?php

namespace App\Models;

use App\Enums\NewsStatus;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class News extends Model
{
    use HasFactory, HasSlug;

    protected $casts = [
        'id' => 'integer',
        'status' => NewsStatus::class,
        'publish_date' => 'timestamp',
        'author_id' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }


    public function publish(): void
    {
        $this->status = NewsStatus::PUBLISHED;
        $this->save();
    }

    public function reject(): void
    {
        $this->status = NewsStatus::REJECTED;
        $this->save();
    }

    public static function getForm(): array
    {
        return [
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(400),
                    DateTimePicker::make('publish_date')
                        ->seconds(false)
                        ->default(now())
                        ->native(false)
                        ->minDate(now())
                        ->firstDayOfWeek(1),
                    TextInput::make('slug')
                        ->hiddenOn('create')
                        ->required()
                        ->columnSpanFull(),
                ]),
            Section::make()
                ->schema([
                    RichEditor::make('description')
                        ->required()
                        ->columnSpanFull(),
                    RichEditor::make('short_description')
                        ->required()
                        ->columnSpanFull(),

                ]),
            Section::make()
                ->columns(2)
                ->schema([
                    Select::make('author_id')
                        ->relationship('author', 'name')
                        ->required(),
                    TextInput::make('status')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('sort')
                        ->numeric(),
                    TextInput::make('place')
                        ->maxLength(255),

                ])
        ];

    }

//    Scopes
    public function scopeTabsCondition(Builder $query): void
    {
        $query->where([
            ['status', NewsStatus::PUBLISHED],
            ['created_at' > Carbon::now()->subMonth()]
        ]);
    }

//    Relations

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
