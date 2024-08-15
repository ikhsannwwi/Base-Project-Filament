<?php

namespace App\Filament\Resources\LogSystemResource\Pages;

use App\Filament\Resources\LogSystemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogSystems extends ListRecords
{
    protected static string $resource = LogSystemResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
