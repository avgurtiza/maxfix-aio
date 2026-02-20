<?php

namespace App\Providers\Filament;

use App\Filament\Resources\AdminUserResource;
use App\Filament\Resources\MaintenanceReminderResource;
use App\Filament\Resources\ServiceRecordResource;
use App\Filament\Resources\ServiceShopResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\VehicleResource;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use App\Models\AdminUser;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->brandName('MaxFix Admin')
            ->brandLogo(asset('images/logo.svg'))
            ->resources([
                AdminUserResource::class,
                UserResource::class,
                VehicleResource::class,
                ServiceRecordResource::class,
                ServiceShopResource::class,
                MaintenanceReminderResource::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('User Management')
                    ->icon('heroicon-o-users')
                    ->collapsible(),
                NavigationGroup::make('Vehicle Management')
                    ->icon('heroicon-o-truck')
                    ->collapsible(),
                NavigationGroup::make('Service Management')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->collapsible(),
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('admin')
            ->authPasswordBroker('admin');
    }
}
