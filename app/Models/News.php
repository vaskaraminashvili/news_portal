<?php

namespace App\Models;

use App\Enums\News\NewsLayout;
use App\Enums\News\NewsPlace;
use App\Enums\News\NewsStatus;
use Carbon\Carbon;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
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
use Wallo\FilamentSelectify\Components\ButtonGroup;

class News extends Model
{
    use HasFactory, HasSlug;

    protected $casts = [
        'id' => 'integer',
        'status' => NewsStatus::class,
        'layout' => NewsLayout::class,
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
                    Select::make('category_id')
                        ->searchable()
                        ->preload()
                        ->relationship('categories', 'name')
                        ->multiple()
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('title')
                        ->required()
                        ->maxLength(400),
                    DateTimePicker::make('publish_date')
                        ->seconds(false)
                        ->default(now())
                        ->native(false)
                        ->minDate(now())
                        ->firstDayOfWeek(1)
                        ->required(),
                    TextInput::make('slug')
                        ->hiddenOn('create')
                        ->required()
                        ->columnSpanFull()
                        ->readOnly(),
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
                        ->required()
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('status')
                        ->options(NewsStatus::class)
                        ->required(),
                    TextInput::make('sort')
                        ->minValue(1)
                        ->step(1)
                        ->numeric(),
                    Select::make('place')
                        ->options(NewsPlace::class),
                    ButtonGroup::make('layout')
                        ->options(NewsLayout::class)
                        ->onColor('primary')
                        ->offColor('gray')
                        ->gridDirection('row')
                        ->default(NewsLayout::DEFAULT)
                        ->columnSpanFull()
                        ->icons(NewsLayout::icons())


                ]),

            Actions::make(actions: [
                Action::make('star')
                    ->label('Fill with factory')
                    ->icon('heroicon-m-star')
                    ->visible(function (string $operation){
                        if ($operation !== 'create'){
                            return false;
                        }
                        if (!app()->environment('local')){
                            return false;
                        }

                        return true;
                    })
//                    ->requiresConfirmation()
                    ->action(function ($livewire) {
                        $data = News::factory()->has(Category::factory()->has(Category::factory())->count(1))->make()->toArray();
                        $data['category_id'] = [rand(1, 30)];
                        $livewire->form->fill($data);
                    }),
            ]),

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
