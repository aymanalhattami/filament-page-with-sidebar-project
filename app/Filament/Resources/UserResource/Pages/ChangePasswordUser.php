<?php

namespace App\Filament\Resources\UserResource\Pages;

use Hash;
use App\Models\User;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use App\Filament\Resources\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasFormActions;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Card;

class ChangePasswordUser extends Page
{
    use HasFormActions;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.change-password-user';

    public User $record;

    public $password;
    public $password_confirmation;
    public $reason;

    protected function getShieldRedirectPath(): string
    {
        return redirect()->back()->getTargetUrl();
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->url($this->previousUrl ?? static::getResource()::getUrl())
            ->color('secondary');
    }

    public function getBreadcrumb(): ?string
    {
        return trans("Change Password");
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    TextInput::make('password')->label(__('Password'))
                        ->password()
                        ->required()
                        ->minLength(6)
                        ->confirmed(),
                    TextInput::make('password_confirmation')->label(__('Password confirmation'))
                        ->password()
                        ->required(),
                    Textarea::make('reason')->label(__('Reason'))
                        ->required()
                        ->minLength(5)
                ])
        ];
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function () {

            $this->record->update([
                'password' => Hash::make($this->password),
            ]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($this->record)
                ->event('change password')
                ->log($this->reason);
        });

        unset($this->password);
        unset($this->password_confirmation);
        unset($this->reason);

        Notification::make()
            ->title('Password Changeed Successfully')
            ->success()
            ->send();
    }
}
