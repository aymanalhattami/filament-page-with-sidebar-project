<?php

namespace App\Filament\Resources;

use App\CoreLogic\Enums\LanguageEnum;
use App\CoreLogic\Enums\StatusEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\DashboardUser;
use App\Filament\Resources\UserResource\Pages\ListActivitiesUser;
use App\Filament\Resources\UserResource\Pages\ListUserActivitiesUser;
use App\Models\User;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationGroup(): ?string
    {
        return __('Users & Roles');
    }

    public static function getModelLabel(): string
    {
        return __('Users');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function sidebar(Model $record): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->setTitle($record->name)
            ->setDescription($record->created_at)
            ->setNavigationItems([
                PageNavigationItem::make('View User')
                    ->translateLabel()
                    ->url(static::getUrl('view', ['record' => $record->id]))->icon('heroicon-o-rectangle-stack')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.view');
                    })
                    ->visible(true)
                    ->group('User')
                    // ->sort(2)
                    ,
                PageNavigationItem::make('Edit User')
                    ->translateLabel()
                    ->url(static::getUrl('edit', ['record' => $record->id]))
                    ->icon('heroicon-o-rectangle-stack')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.edit');
                    })
                    ->visible(true)
                    ->group('User')
                    // ->sort(1)
                    ,
                PageNavigationItem::make('Manage User')
                    ->translateLabel()
                    ->url(static::getUrl('manage', ['record' => $record->id]))
                    ->icon('heroicon-o-rectangle-stack')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.manage');
                    })->visible(true)
                    // ->group('User 2')
                    ,
                PageNavigationItem::make('Change Password')
                    ->translateLabel()
                    ->url(static::getUrl('password.change', ['record' => $record->id]))
                    ->icon('heroicon-o-rectangle-stack')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.password.change');
                    })
                    ->visible(true),
                PageNavigationItem::make('User Activities')
                    ->translateLabel()
                    ->url(static::getUrl('activities.user', ['record' => $record->id]))->icon('heroicon-o-rectangle-stack')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.activities.user');
                    })
                    ->visible(true)
                    ->badge(Activity::query()->where([['causer_type', '=', User::class], ['causer_id', '=', $record->id]])->count()),
                PageNavigationItem::make('Record Activities')
                    ->translateLabel()
                    ->url(static::getUrl('activities', ['record' => $record->id]))
                    ->icon('heroicon-o-rectangle-stack')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.activities');
                    })
                    ->badge(Activity::query()->where([['subject_type', '=', User::class], ['subject_id', '=', $record->id]])->count())
                    ->visible(function(){
                        return true;
                    }),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->translateLabel()
                            ->required()
                            ->maxLength(191),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->translateLabel()
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(191),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email verified at')
                            ->translateLabel()
                            ->hiddenOn(['edit', 'create']),
                        Forms\Components\TextInput::make('password')
                            ->translateLabel()
                            ->password()
                            ->required()
                            ->maxLength(191)
                            ->minLength(6)
                            ->hiddenOn(['edit', 'view']),
                        Select::make('language')
                            ->label('Language')
                            ->translateLabel()
                            ->options(LanguageEnum::toArray())
                            ->searchable()
                            ->required(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Created at')
                            ->translateLabel()
                            ->hiddenOn(['create', 'edit']),
                        Forms\Components\DateTimePicker::make('updated_at')
                            ->label('Updated at')
                            ->translateLabel()
                            ->hiddenOn(['create', 'edit']),
                        Select::make('roles')
                            ->label('Roles')
                            ->translateLabel()
                            ->options(Role::pluck('name', 'id'))
                            ->multiple()
                            ->searchable()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->columnSpan(2),
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->label('Avatar')
                            ->translateLabel()
                            ->columnSpan(2),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('email')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('roles')
                    ->translateLabel()
                    ->formatStateUsing(fn (string $state): string => optional(json_decode($state))[0]?->name ?? ''),
                Tables\Columns\TextColumn::make('status')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime(),
            ])
            ->filters(
                [
                    SelectFilter::make('status')
                        ->translateLabel()
                        ->searchable()
                        ->options(StatusEnum::forUserToArray()),
                    SelectFilter::make('roles')
                        ->translateLabel()
                        ->searchable()
                        ->relationship('roles', 'name'),

                    Filter::make('name')
                        ->translateLabel()
                        ->form([
                            Forms\Components\TextInput::make('name'),
                        ])->query(function (Builder $query, array $data): Builder {
                            return $query->where('name', 'like', "%{$data['name']}%");
                        }),

                    Filter::make('email')
                        ->translateLabel()
                        ->form([
                            Forms\Components\TextInput::make('email'),
                        ])->query(function (Builder $query, array $data): Builder {
                            return $query->where('email', 'like', "%{$data['email']}%");
                        }),

                    Filter::make('created_at')
                        ->translateLabel()
                        ->form([
                            Forms\Components\DatePicker::make('created_from')
                                ->translateLabel(),
                            Forms\Components\DatePicker::make('created_until')
                                ->default(now())->translateLabel(),
                        ])->query(function (Builder $query, array $data): Builder {
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

                ],
            )
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('manage')->url(function (User $record) {
                    return static::getNavigationUrl() . '/' . $record->id . '/manage';
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}/view'),
            'manage' => Pages\ManageUser::route('/{record}/manage'),
            'password.change' => Pages\ChangePasswordUser::route('/{record}/password/change'),
            'activities' => ListActivitiesUser::route('/{record}/activities'),
            'activities.user' => ListUserActivitiesUser::route('/{record}/activities/user'),
        ];
    }
}
