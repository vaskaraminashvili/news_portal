<?php

namespace App\Filament\Admin\Resources\NewsResource\Pages;

use App\Enums\NewsStatus;
use App\Filament\Admin\Resources\NewsResource;
use App\Models\News;
use Carbon\Carbon;
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
            'published' => Tab::make('Last Month Published')
                ->badge(
                    News::query()->where([
                        ['status', NewsStatus::PUBLISHED],
//                        ['created_at' > Carbon::now()->subMonth()]
                    ])
                        ->count())
                ->badgeColor('success')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where([
                        ['status', NewsStatus::PUBLISHED],
                        ['created_at' > Carbon::now()->subMonth()]
                    ]);
                }),
            'pending' => Tab::make('Last Month Pending')
                ->badge(News::query()
                    ->where('status', NewsStatus::PENDING)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(function ($query) {
                    $query->where('status', NewsStatus::PENDING)->where('created_at' > Carbon::now()->subMonth());
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
