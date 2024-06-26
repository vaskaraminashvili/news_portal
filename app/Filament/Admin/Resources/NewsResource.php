<?php

namespace App\Filament\Admin\Resources;

use App\Enums\NewsStatus;
use App\Filament\Admin\Resources\NewsResource\Pages;
use App\Filament\Admin\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema(News::getform());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action) {
                return $action->button()
                    ->label('Filters');
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('publish_date')
                    ->since()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->limit(15)
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options(NewsStatus::class)
//                    ->badge()
                    ->selectablePlaceholder(false)
                    ->alignCenter()
//                    ->color(function ($state) {
//                        return $state->getColor();
//                    })
                    ->afterStateUpdated(function () {
                        Notification::make()->success()->title('Status Updated')->send();
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('views')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('place')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('publish_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('author')
                    ->relationship('author', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('is_published')
                    ->label('show only published')
                    ->toggle()
                    ->query(function ($query) {
                        return $query->where('status', 'Published');
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('publish')
                        ->visible(function ($record) {
                            return $record->status !== NewsStatus::PUBLISHED;
                        })
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (News $record) {
                            $record->publish();
                        })
                        ->after(function () {
                            Notification::make()->duration(500)->success()->title('News was published!')->send();
                        }),
                    Tables\Actions\Action::make('reject')
                        ->visible(function ($record) {
                            return $record->status !== NewsStatus::REJECTED;
                        })
                        ->requiresConfirmation()
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->action(function (News $record) {
                            $record->reject();
                        })
                        ->after(function () {
                            Notification::make()->duration(500)->danger()->title('News was rejected!')->send();
                        })
                ])
//                ->button()
//                ->color('success')
//                ->label('Actions')
                ,

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-check')
                        ->action(function (Collection $records) {
                            $records->each->publish();
                        })->after(function () {
                            Notification::make()->success()->title('All News were published!')->send();
                        })

                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Export')
                    ->tooltip('this will export what you see')
                    ->action(function ($livewire) {
//                        dd($livewire->getFilteredTableQuery()->get());
                        //                    export action here
                    })
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
//            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
