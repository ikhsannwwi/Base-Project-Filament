<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\AdminMenu;
use App\Models\UserGroup;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\UserGroupPermission;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\UserGroupResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserGroupResource\RelationManagers;

class UserGroupResource extends Resource
{
    protected static ?string $model = UserGroup::class;

    protected static ?string $navigationIcon = 'heroicon-m-user-group';
    
    protected static ?string $navigationGroup = 'User Managements';

    protected static $module = 'User Group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Enter name menu')
                            ->maxLength(255)
                            ->required()
                            ->columnSpan(2),

                        Toggle::make('status')
                            ->label('Active')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(true)
                            ->helperText('Toggle to activate or deactivate')
                            ->columnSpan(1),
                    ]),

                    Repeater::make('UserGroupPermission')
                        ->relationship()
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('admin_menu_id')
                                        ->label('Admin Menu')
                                        ->options(function (UserGroup $usergroup) {
                                            $usedMenuIds = $usergroup->load('UserGroupPermission')->UserGroupPermission->pluck('admin_menu_id')->toArray();
                                            $admin_menu = AdminMenu::whereNotIn('id', $usedMenuIds)->pluck('name', 'id')->toArray();
                                            return $admin_menu;
                                        })
                                        ->suffixIcon('heroicon-c-bars-4')
                                        ->searchable()
                                        ->required()
                                        ->columnSpan(1),

                                    Grid::make(5)
                                        ->schema([
                                            Checkbox::make('index')->label('Index')->default(true),
                                            Checkbox::make('create')->label('Create')->default(true),
                                            Checkbox::make('edit')->label('Edit')->default(true),
                                            Checkbox::make('destroy')->label('Destroy')->default(true),
                                            Checkbox::make('view')->label('View')->default(true),
                                        ]),
                                ])
                        ])
                        ->deleteAction(
                            function (Action $action) {
                                return $action
                                    ->requiresConfirmation()
                                    ->modalDescription('Are you sure you\'d like to delete this item? This cannot be undone.')
                                    ->after(function ($state, array $arguments) {
                                        $id = str_replace('record-', '', $arguments['item']);
                                        UserGroupPermission::destroy($id);
                                        Notification::make()
                                                 ->success()
                                                 ->title('Permission Deleted')
                                                 ->body('The permission has been permanently deleted.')
                                                 ->send();
                                    });
                            }
                        )
                        ->addAction(
                            function (Action $action) {
                                return $action
                                    ->after(function ($state, array $arguments, UserGroup $usergroup) {
                                        // $usedMenuIds = $usergroup->load('UserGroupPermission')->UserGroupPermission->pluck('admin_menu_id')->toArray();
                                        // $admin_menu = AdminMenu::whereNotIn('id', $usedMenuIds)->first();
                                        // $data = [
                                        //     'user_group_id' => $usergroup->id,
                                        //     'admin_menu_id' => $admin_menu->id,
                                        //     'index' => true,
                                        //     'create' => true,
                                        //     'edit' => true,
                                        //     'destroy' => true,
                                        //     'view' => true,
                                        // ];
                                        // UserGroupPermission::create($data);
                                        Notification::make()
                                                 ->success()
                                                 ->title('Permission Added')
                                                 ->body('A new permission has been added successfully.')
                                                 ->send();
                                    });
                            }
                        )
                        ->defaultItems(1)
                        ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('status')
                ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                ->label('Status')
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
            'index' => Pages\ListUserGroups::route('/'),
            'create' => Pages\CreateUserGroup::route('/create'),
            'edit' => Pages\EditUserGroup::route('/{record}/edit'),
        ];
    }
}
