<?php

namespace App\Filament\Admin\Resources\NewsResource\Pages;

use App\Enums\NewsStatus;
use App\Filament\Admin\Resources\NewsResource;
use App\Models\News;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All News'),
            'published' => Tab::make('Published')->modifyQueryUsing(function ($query) {
                $query->where('status', NewsStatus::PUBLISHED);
            }),
            'pending' => Tab::make('Pending')->modifyQueryUsing(function ($query) {
                $query->where('status', NewsStatus::PENDING);
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
