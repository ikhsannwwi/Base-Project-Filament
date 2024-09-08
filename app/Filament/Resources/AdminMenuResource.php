<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\AdminMenu;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Helpers\LogSystemHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AdminMenuResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AdminMenuResource\RelationManagers;

class AdminMenuResource extends Resource
{
    protected static ?string $model = AdminMenu::class;

    protected static ?string $navigationIcon = 'heroicon-c-bars-4';

    protected static ?string $navigationGroup = 'Setting Managements';

    protected static $module = 'Admin Menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->label('Name')
                                            ->placeholder('Enter name menu')
                                            ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('identifier'),
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('position')
            ->reorderable('position');
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
            'index' => Pages\ListAdminMenus::route('/'),
            'create' => Pages\CreateAdminMenu::route('/create'),
            'edit' => Pages\EditAdminMenu::route('/{record}/edit'),
        ];
    }
}
