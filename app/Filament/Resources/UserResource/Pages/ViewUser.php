<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;

class ViewUser extends ViewRecord
{
    use HasPageSidebar;

    protected static string $resource = UserResource::class;

    // protected static string $view = 'filament.resources.user-resource.pages.view-user';
}
