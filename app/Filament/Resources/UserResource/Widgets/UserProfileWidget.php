<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class UserProfileWidget extends Widget
{
    protected static string $view = 'filament.resources.user-resource.widgets.user-profile-widget';

    public ?Model $record = null;

    protected int | string | array $columnSpan = 2;
}
