<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\CoreLogic\Enums\StatusEnum;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Widgets\UserClosedWidget;
use App\Filament\Resources\UserResource\Widgets\UserStatusWidget;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class ManageUser extends Page
{
    use InteractsWithForms, InteractsWithActions;

    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.user-resource.pages.manage-user';
    public $status;
    public $reason;
    public User $record;

    public function getBreadcrumb(): ?string
    {
        return __('Manage User');
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    Select::make('status')->label('Status')
                        ->translateLabel()
                        ->options(function () {
                            $filteredArray = [];
                            foreach (StatusEnum::forUserToArray() as $key => $value) {
                                if ($key != $this->record->status) {
                                    $filteredArray[$key] = $value;
                                }
                            }

                            return $filteredArray;
                        })
                        ->searchable()
                        ->required(),
                    Textarea::make('reason')
                        ->translateLabel()
                        ->label('Reason')
                        ->required()
                        ->minLength(5),
                ]),
        ];
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function () {
            $oldStatus = $this->record->status;

            $this->record->status = $this->status;
            $this->record->save();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($this->record)
                ->event('manage')
                ->withProperties([
                    'status' => [
                        'old_value' => $oldStatus,
                        'new_value' => $this->status,
                    ],
                ])
                ->log($this->reason);
        });

        unset($this->status);
        unset($this->reason);

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    public function saveAction(): Action
    {
        return Action::make('save')
            ->action(fn () => $this->save());
    }
}
