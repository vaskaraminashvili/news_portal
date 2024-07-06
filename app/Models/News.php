<?php

namespace App\Models;

use App\Models\NewsPlace;
use App\Enums\NewsPlace as NewsPlaceEnum;
use App\Enums\NewsStatus;
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
                        ->options(NewsPlaceEnum::class),

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

    public function places()
    {
        return $this->hasMany(NewsPlace::class);
    }

    public function addPlace($place)
    {
        return $this->places()->create(['place_id' => $place]);
    }

    public function removePlace($place)
    {
        return $this->places()->where('place_id', $place)->delete();
    }

    public function hasPlace($place)
    {
        return $this->places()->where('place_id', $place)->exists();
    }
}
