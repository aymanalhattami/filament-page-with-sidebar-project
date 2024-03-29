<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ListUserActivitiesUser extends Page implements HasTable
{
    use InteractsWithTable, HasPageSidebar;

    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.user-resource.pages.list-user-activities-user';
    public User $record;

    public function getTitle(): string
    {
        return __('List User Activities');
    }

    public function getBreadcrumb(): ?string
    {
        return __('List Record Activities');
    }

    public function getTableQuery(): Builder
    {
        return Activity::query()->where([
            ['causer_type', '=', User::class],
            ['causer_id', '=', $this->record->id],
        ]);
    }

    public function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->translateLabel()
                ->toggleable()
                ->searchable(),
            TextColumn::make('log_name')
                ->translateLabel()
                ->toggleable()
                ->searchable(),
            TextColumn::make('event')
                ->translateLabel()
                ->toggleable()
                ->searchable(),
            TextColumn::make('subject_type')
                ->label('Subject')
                ->toggleable()
                ->translateLabel()
                ->description(function (Activity $record) {
                    return $record->subject_id;
                }),
            TextColumn::make('created_at'),
        ];
    }

    public function getTableFilters(): array
    {
        return [
            SelectFilter::make('log_name')
                ->options([
                    'Resource' => 'Resource',
                    'Access' => 'Access',
                ])
                ->searchable(),
            SelectFilter::make('event')
                ->options([
                    'Created' => 'Created',
                    'Updated' => 'Updated',
                    'Login' => 'Login',
                ])
                ->searchable(),
            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from')->label(trans('From Date')),
                    DatePicker::make('created_until')->label(trans('To Date'))->default(now()),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),
        ];
    }

    public function getTableActions(): array
    {
        return [
            ViewAction::make()->form(function () {
                return [
                    TextInput::make('id'),
                    TextInput::make('log_name'),
                    TextInput::make('event'),
                    TextInput::make('description'),
                    TextInput::make('subject_type'),
                    TextInput::make('subject_id'),
                    TextInput::make('causer_type'),
                    TextInput::make('created_at'),
                    TextInput::make('updated_at'),
                ];
            }),
        ];
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('id'),
            TextInput::make('log_name'),
        ];
    }
}
