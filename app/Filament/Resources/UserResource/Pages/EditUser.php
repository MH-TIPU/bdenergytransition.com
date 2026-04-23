<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Prevent removing admin from super-admin account
        $super = env('SEED_ADMIN_EMAIL', 'admin@admin.com');
        if ($this->record->email === $super && array_key_exists('is_admin', $data) && ! $data['is_admin']) {
            Notification::make()
                ->warning()
                ->title('Cannot remove admin privileges from the Super Admin')
                ->send();

            $data['is_admin'] = true;
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Don't overwrite password when empty
            unset($data['password']);
        }

        return $data;
    }
}
