<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\UserGroup;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-m-user';

    protected static ?string $navigationGroup = 'User Managements';

    protected static $module = 'User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_group_id')
                                        ->label('User Group')
                                        ->options(
                                            UserGroup::all()->pluck('name', 'id')->toArray()
                                        )
                                        ->searchable()
                                        ->required(),
                Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->label('Full Name')
                                            ->placeholder('Enter your full name')
                                            ->maxLength(255),
                Forms\Components\TextInput::make('email')
                                            ->required()
                                            ->email()
                                            ->label('Email Address')
                                            ->placeholder('Enter your email address')
                                            ->maxLength(255)
                                            ->unique('users', 'email', ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                                            ->password()
                                            ->label('Password')
                                            ->placeholder('Enter a secure password')
                                            ->dehydrated(fn ($state) => filled($state)) 
                                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                Forms\Components\Toggle::make('status')
                                        ->label('Active')
                                        ->inline(false)
                                        ->onColor('success')
                                        ->offColor('danger')
                                        ->default(true)
                                        ->helperText('Toggle to activate or deactivate'),
            ]);
    }

    protected function handleRecordUpdate(array $data): void
    {
        $user = $this->getRecord();

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'user_group_id' => $data['user_group_id'],
        ]);

        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('usergroup.name')->label('User Group'),
                Tables\Columns\TextColumn::make('status')
                ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                ->label('Status'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
