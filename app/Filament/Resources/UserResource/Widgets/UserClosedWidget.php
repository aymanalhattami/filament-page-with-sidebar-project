<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class UserClosedWidget extends Widget
{
    protected static string $view = 'filament.resources.user-resource.widgets.user-closed-widget';

    public ?Model $record = null;
}
