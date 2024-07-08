<?php

namespace App\Filament\Admin\Resources\NewsResource\Pages;

use App\Enums\News\NewsStatus;
use App\Filament\Admin\Resources\NewsResource;
use App\Models\News;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All News'),
            'published' => Tab::make('Published News')
                ->badge(
                    News::query()->where('status', NewsStatus::PUBLISHED)
                        ->count())
                ->badgeColor('success')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->where('status', NewsStatus::PUBLISHED)
                        ->orderBy('publish_date', 'desc');
                }),
            'pending' => Tab::make('Pending News')
                ->badge(News::query()
                    ->where('status', NewsStatus::PENDING)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(function ($query) {
                    $query
                        ->where('status', NewsStatus::PENDING)
                        ->orderBy('publish_date', 'desc');
                })
        ];

    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
