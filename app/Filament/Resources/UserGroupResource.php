<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\AdminMenu;
use App\Models\UserGroup;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\UserGroupPermission;
use Illuminate\Database\Eloquent\Builder;
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
        $adminMenus = AdminMenu::all();
        $userGroup = $form->model;

        $permissions = $userGroup !== "App\Models\UserGroup"
            ? UserGroupPermission::where('user_group_id', $userGroup->id)->get()->keyBy('admin_menu_id')
            : collect();
            // dd($permissions->get(5));

        $checkboxSchemas = [];
        foreach ($adminMenus as $menu) {
            $permission = $permissions->get($menu->id);

            $checkboxSchemas[] = Forms\Components\Fieldset::make($menu->name)
            ->schema([
                Forms\Components\CheckboxList::make("permissions.{$menu->id}")
                    ->label('')
                    ->columns(2)
                    ->bulkToggleable()
                    ->options([
                        'index' => 'index',
                        'view' => 'view',
                        'create' => 'Create',
                        'edit' => 'Edit',
                        'destroy' => 'Destroy',
                    ])
            ]);
        }

        return $form
            ->schema(array_merge([
                Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->label('Name')
                                            ->placeholder('Enter name menu')
                                            ->maxLength(255),
                Forms\Components\Toggle::make('status')
                                        ->label('Active')
                                        ->inline(false)
                                        ->onColor('success')
                                        ->offColor('danger')
                                        ->default(true)
                                        ->helperText('Toggle to activate or deactivate'),
            ], $checkboxSchemas));
    }

    public static function savePermissions(UserGroup $userGroup, array $permissions): void
    {
        foreach ($permissions as $menuId => $actions) {
            try {
                UserGroupPermission::where('user_group_id', $userGroup->id)->updateOrCreate(
                    ['admin_menu_id' => $menuId],
                    [
                        'user_group_id' => $userGroup->id,
                        'index' => isset($actions[0]) ? 1 : 0,
                        'view' => isset($actions[1]) ? 1 : 0,
                        'create' => isset($actions[2]) ? 1 : 0,
                        'edit' => isset($actions[3]) ? 1 : 0,
                        'destroy' => isset($actions[4]) ? 1 : 0,
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('Failed to save permissions', ['error' => $e->getMessage(), 'menu_id' => $menuId]);
            }
        }
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
