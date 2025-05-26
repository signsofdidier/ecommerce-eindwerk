<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        // Zorg ervoor dat tenantCompanies geladen zijn voor het formulier
        $this->record->load('tenantCompanies');
    }

    protected function afterSave(): void
    {
        $company = auth()->user()->tenantCompanies()->first()
            ?? auth()->user()->companies()->first();

        if ($company && isset($this->data['pivot_role'])) {
            $this->record->tenantCompanies()->updateExistingPivot(
                $company->id,
                ['role' => $this->data['pivot_role']]
            );
        }
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
