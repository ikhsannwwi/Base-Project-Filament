<?php

namespace App\Filament\Pages;

use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

class Settings extends BaseSettings
{

    protected static ?string $navigationGroup = 'Setting Managements';
    
    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('General')
                        ->schema([
                            TextInput::make('general.brand_name')
                                ->required(),
                            Grid::make([
                                'default' => 1,
                                'md' => 2,
                                ])->schema([
                                    Select::make('general.timezone')
                                        ->options([
                                            'UTC' => 'UTC',
                                            'Asia/Jakarta' => 'Asia/Jakarta',
                                        ])
                                        ->required(),
                                    Select::make('general.locale')
                                        ->options([
                                            'en' => 'en',
                                            'id' => 'id',
                                        ])
                                        ->required(),
                            ]),
                            Section::make('Color App')
                                ->description('Set your App design with color picker')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 3,
                                        'lg' => 4,
                                        'xl' => 6,
                                        '2xl' => 8,
                                    ])->schema([
                                        ColorPicker::make('general.color.danger'),
                                        ColorPicker::make('general.color.gray'),
                                        ColorPicker::make('general.color.info'),
                                        ColorPicker::make('general.color.primary'),
                                        ColorPicker::make('general.color.success'),
                                        ColorPicker::make('general.color.warning'),
                                    ])
                                ]),
                            FileUpload::make('general.media.favicon')
                                ->image()
                                ->directory('administrator/assets/media/settings')
                                ->imageResizeMode('cover')
                                ->imageCropAspectRatio('1:1'),
                            FileUpload::make('general.media.logo')
                                ->image()
                                ->directory('administrator/assets/media/settings')
                                ->imageResizeMode('cover')
                        ]),
                    Tabs\Tab::make('SMTP')
                        ->schema([
                            Select::make('smtp.mailer')
                                ->options([
                                    'smtp' => 'smtp',
                                ]),
                            TextInput::make('smtp.host'),
                            TextInput::make('smtp.port'),
                            TextInput::make('smtp.username'),
                            TextInput::make('smtp.password')
                                ->password(),
                            Select::make('smtp.encryption')
                                ->options([
                                    'tls' => 'TLS',
                                    'ssl' => 'SSL',
                                ]),
                            TextInput::make('smtp.from_name'),
                            TextInput::make('smtp.from_address')
                                ->email(),
                        ]),
                    Tabs\Tab::make('Developer')
                        ->schema([
                            Toggle::make('developer.debug')
                                    ->label('True')
                                    ->inline(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->default(true)
                                    ->helperText('Toggle to true or false Debug'),
                            TextInput::make('developer.base_url')
                                    ->default('http://localhost'),
                            TextInput::make('developer.asset_url'),
                        ]),
                ]),
        ];
    }
}
