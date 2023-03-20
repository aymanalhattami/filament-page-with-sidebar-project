<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Spatie\Activitylog\Models\Activity;
use App\Filament\Resources\UserResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;

class ListUserActivitiesUser extends Page implements HasTable
{
    use InteractsWithTable;
    use HasPageShield;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.list-user-activities-user';

    public function mount($record)
    {
        $this->record = User::find($record);
    }

    protected static function getPermissionName(): string
    {
        return 'user_activities_user';
    }

    protected function getShieldRedirectPath(): string
    {
        return redirect()->back()->getTargetUrl();
    }

    public function hasTableColumnSearches(): bool
    {
        return true;
    }

    protected function getTitle(): string
    {
        return '';
    }

    public function getBreadcrumb(): ?string
    {
        return trans("Mange User");
    }

    protected function getTableQuery(): Builder
    {
        return Activity::query()->where([
            ['causer_type', '=', User::class],
            ['causer_id', '=', $this->record->id],
        ]);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id'),
            TextColumn::make('log_name'),
            TextColumn::make('event'),
            TextColumn::make('subject_type')->label('Subject')
            ->description(function (Activity $record) {
                return $record->subject_id;
            }),
            // TextColumn::make('subject_id'),
            TextColumn::make('created_at'),
        ];
    }

    protected function getTableFilters(): array
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
                })
        ];
    }

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->where('event', $searchQuery);
        }

        return $query;
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 2;
    }

    protected function getTableActions(): array
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
                    TextInput::make('causer_type')
                    // ->view('components.alerts.warning')
                    ,
                    // Card::make()
                    //     ->statePath('currency')
                    //     ->schema([
                    //         TextInput::make('code'),
                    //         TextInput::make('name'),
                    //         TextInput::make('symbol'),
                    //     ]),
                    TextInput::make('created_at'),
                    TextInput::make('updated_at'),
                ];
            }),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('id'),
            TextInput::make('log_name'),
        ];
    }
}
