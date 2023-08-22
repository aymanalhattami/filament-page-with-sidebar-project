<?php

namespace App\Filament\Resources;

use Str;
use Closure;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Tables\Actions\ViewAction;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use App\CoreLogic\Enums\StatusEnum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use App\CoreLogic\Enums\LanguageEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Navigation\NavigationItem;
use Filament\Tables\Columns\TextColumn;
use Spatie\Activitylog\Models\Activity;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\UserResource\Pages\DashboardUser;
use App\Filament\Resources\UserResource\Pages\ListActivitiesUser;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use App\Filament\Resources\UserResource\Pages\ListActivityUser;
use App\Filament\Resources\UserResource\Pages\ListUserActivity;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Filament\Resources\UserResource\Pages\ListRecordActivity;
use App\Filament\Resources\UserResource\Pages\ListRecordActivityUser;
use App\Filament\Resources\UserResource\Pages\ListUserActivitiesUser;
use Filament\Forms\Components\Card;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function getNavigationGroup(): ?string
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

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function sidebar(Model $record): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->setTitle($record->name)
            ->setDescription($record->created_at)
            ->setNavigationItems([
                PageNavigationItem::make('User Dashboard')
                    ->translateLabel()
                    ->url(function () use ($record) {
                        return static::getUrl('dashboard', ['record' => $record->id]);
                    })->icon('heroicon-o-collection')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.dashboard');
                    })->isHiddenWhen(false),
                PageNavigationItem::make('View User')
                    ->translateLabel()
                    ->url(function () use ($record) {
                        return static::getUrl('view', ['record' => $record->id]);
                    })->icon('heroicon-o-collection')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.view');
                    })->isHiddenWhen(false),
                PageNavigationItem::make('Edit User')
                    ->translateLabel()
                    ->url(function () use ($record) {
                        return static::getUrl('edit', ['record' => $record->id]);
                    })->icon('heroicon-o-collection')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.edit');
                    })
                    ->isHiddenWhen(false),
                PageNavigationItem::make('Manage User')
                    ->translateLabel()
                    ->url(function () use ($record) {
                        return static::getUrl('manage', ['record' => $record->id]);
                    })->icon('heroicon-o-collection')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.manage');
                    })->isHiddenWhen(false),
                PageNavigationItem::make("Change Password")
                    ->translateLabel()
                    ->url(function () use ($record) {
                        return static::getUrl('password.change', ['record' => $record->id]);
                    })->icon('heroicon-o-collection')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.password.change');
                    })
                    ->isHiddenWhen(false),
                PageNavigationItem::make('User Activities')
                    ->translateLabel()
                    ->url(function () use ($record) {
                        return static::getUrl('activities.user', ['record' => $record->id]);
                    })->icon('heroicon-o-collection')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.activities.user');
                    })
                    ->isHiddenWhen(false)
                    ->badge(Activity::query()->where([['causer_type', '=', User::class], ['causer_id', '=', $record->id]])->count()),
                PageNavigationItem::make('Record Activities')
                    ->translateLabel()
                    ->url(function () use ($record) {
                        return static::getUrl('activities', ['record' => $record->id]);
                    })->icon('heroicon-o-collection')
                    ->isActiveWhen(function () {
                        return request()->routeIs(static::getRouteBaseName() . '.activities');
                    })
                    ->badge(Activity::query()->where([['subject_type', '=', User::class], ['subject_id', '=', $record->id]])->count())
                    ->isHiddenWhen(false),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
            'dashboard' => DashboardUser::route('/{record}/dashboard'),
        ];
    }
}
