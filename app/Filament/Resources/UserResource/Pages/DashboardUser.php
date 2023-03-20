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
    use HasPageShield;
    
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.dashboard-user';

    public function mount($record)
    {
        $this->record = User::find($record);
    }

    protected static function getPermissionName(): string
    {
        return 'dashboard_user';
    }

    protected function getShieldRedirectPath(): string
    {
        return redirect()->back()->getTargetUrl();
    }

    protected function getTitle(): string
    {
        return '';
    }

    public function getBreadcrumb(): ?string
    {
        return trans("User Dashboard");
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UserProfileWidget::class,
            UserStatusWidget::class,
            UserLastLoginWidget::class
        ];
    }
}
