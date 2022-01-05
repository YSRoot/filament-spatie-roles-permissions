<?php

namespace Reksmey\FilamentSpatieRolesPermissions\Resources\RoleResource\Pages;

use Illuminate\Support\Arr;
use Reksmey\FilamentSpatieRolesPermissions\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Permission;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public array $permissions;

    public function beforeCreate(): void
    {
        $this->permissions = array_keys(array_filter(Arr::except($this->data, ['name', 'select_all', 'guard_name'])));
    }

    public function afterCreate(): void
    {
        $permissions = [];
        foreach ($this->permissions as $name) {
            $permissions[] = Permission::findOrCreate($name, $this->record->guard_name);
        }
        $this->record->syncPermissions($permissions);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return Arr::Only($this->data, ['name', 'guard_name']);
    }
}
