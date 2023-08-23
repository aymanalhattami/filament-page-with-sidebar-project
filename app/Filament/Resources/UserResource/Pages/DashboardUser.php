<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Widgets\UserLastLoginWidget;
use App\Filament\Resources\UserResource\Widgets\UserProfileWidget;
use App\Filament\Resources\UserResource\Widgets\UserStatusWidget;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class DashboardUser extends Page
{   
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.dashboard-user';

    public User $record;

    public function getBreadcrumb(): ?string
    {
        return __("User Dashboard");
    }

    public function getHeaderWidgets(): array
    {
        return [
            UserProfileWidget::make([
                'record' => $this->record
            ]),
            // UserStatusWidget::class,
            // UserLastLoginWidget::class
        ];
    }
}
