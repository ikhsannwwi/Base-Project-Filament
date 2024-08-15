<?php

namespace App\Filament\Resources\AdminMenuResource\Pages;

use App\Filament\Resources\AdminMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminMenus extends ListRecords
{
    protected static string $resource = AdminMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
