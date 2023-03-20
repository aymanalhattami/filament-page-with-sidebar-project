<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class UserStatusWidget extends Widget
{
    protected static string $view = 'filament.resources.user-resource.widgets.user-status-widget';

    public ?Model $record = null;

    protected function getViewData(): array
    {
        return [];
    }
}
