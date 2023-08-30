<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;

class EditUser extends EditRecord
{
    use HasPageSidebar;

    protected static string $resource = UserResource::class;
}
