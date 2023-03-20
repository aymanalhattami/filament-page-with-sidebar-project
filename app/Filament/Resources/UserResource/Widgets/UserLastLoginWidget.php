<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class UserLastLoginWidget extends Widget
{
    protected static string $view = 'filament.resources.user-resource.widgets.user-last-login-widget';

    public ?Model $record = null;

    protected int | string | array $columnSpan = 2;
    public $activity;

    public function mount()
    {
        $this->activity = Activity::where('event', 'Login')->latest()->first();
    }
}
