<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Outerweb\Settings\Models\Setting;
use App\Livewire\CustomProfileComponent;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Outerweb\FilamentSettings\Filament\Plugins\FilamentSettingsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->profile()
            ->colors([
                'danger' => Setting::get('general.color.danger') ?? Color::Rose,
                'gray' => Setting::get('general.color.gray') ?? Color::Gray,
                'info' => Setting::get('general.color.info') ?? Color::Blue,
                'primary' => Setting::get('general.color.primary') ?? Color::Indigo,
                'success' => Setting::get('general.color.success') ?? Color::Emerald,
                'warning' => Setting::get('general.color.warning') ?? Color::Orange,
            ])
            ->brandName(Setting::get('general.brand_name') ?? 'Base Project')
            ->favicon(asset('storage/' . Setting::get('general.media.favicon')))
            ->brandLogo(asset('storage/' . Setting::get('general.media.logo')))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authGuard('web')
            ->authMiddleware([
                Authenticate::class,
            ])->plugins([
                FilamentSettingsPlugin::make()
                    ->pages([
                        \App\Filament\Pages\Settings::class,
                    ]),
                FilamentEditProfilePlugin::make()
                ->slug('my-profile')
                ->setTitle('My Profile')
                ->setNavigationLabel('My Profile')
                ->setNavigationGroup('Group Profile')
                ->setIcon('heroicon-o-user')
                ->setSort(10)
                // ->canAccess(fn () => auth()->user()->id === 1)
                ->shouldRegisterNavigation(false)
                ->shouldShowDeleteAccountForm(false)
                ->shouldShowSanctumTokens()
                ->shouldShowBrowserSessionsForm()
                ->shouldShowAvatarForm(
                    value: true,
                    directory: 'administrator/assets/media/profiles',
                    rules: 'mimes:jpeg,png|max:1024'
                )
            ])->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => auth()->user()->name)
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
                    //If you are using tenancy need to check with the visible method where ->company() is the relation between the user and tenancy model as you called
                    // ->visible(function (): bool {
                    //     return auth()->user()->company()->exists();
                    // }),
            ]);
    }
}
