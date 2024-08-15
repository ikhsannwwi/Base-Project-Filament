<?php

namespace App\Filament\Resources\UserGroupResource\Pages;

use Filament\Actions;
use App\Models\UserGroup;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UserGroupResource;

class CreateUserGroup extends CreateRecord
{
    protected static string $resource = UserGroupResource::class;

    protected function handleRecordCreation(array $data): UserGroup
    {
        $userGroup = UserGroup::create([
            'name' => $data['name'],
            'status' => $data['status'],
        ]);

        UserGroupResource::savePermissions($userGroup, $data['permissions']);
        
        $data['id'] = $userGroup->id;

        return $userGroup;
    }
}
