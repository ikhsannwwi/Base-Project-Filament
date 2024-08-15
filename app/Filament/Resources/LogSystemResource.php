<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogSystemResource\Pages;
use App\Filament\Resources\LogSystemResource\RelationManagers;
use App\Models\LogSystem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogSystemResource extends Resource
{
    protected static ?string $model = LogSystem::class;

    protected static ?string $navigationIcon = 'heroicon-c-archive-box-arrow-down';

    protected static ?string $navigationGroup = 'Systems';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ip_address')->label('Ip Address'),
                Forms\Components\TextInput::make('device')->label('Device'),
                Forms\Components\TextInput::make('browser_name')->label('Browser Name'),
                Forms\Components\TextInput::make('browser_version')->label('Browser Version'),
                Forms\Components\TextInput::make('module')->label('Module'),
                Forms\Components\TextInput::make('action')->label('Action'),
                Forms\Components\TextInput::make('data_id')->label('Data ID'),
                Forms\Components\TextArea::make('data')
                                            ->label('Data')
                                            ->rows(10)
                                            ->cols(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
            ])
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                                        ->label('User')
                                        ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')->label('Ip Address'),
                Tables\Columns\TextColumn::make('module')
                                        ->label('Module')
                                        ->searchable()
                                        ->sortable(),
                Tables\Columns\TextColumn::make('action')
                                        ->label('Action')
                                        ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                                        ->label('Date Time')
                                        ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogSystems::route('/'),
        ];
    }
}
