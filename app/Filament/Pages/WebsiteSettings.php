<?php

namespace App\Filament\Pages;

use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Pages\Page;

class WebsiteSettings extends Page
{
    use HasPageSidebar;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.web-site-settings';
    protected static bool $shouldRegisterNavigation = false;

    public static function sidebar(): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->setTitle('Application Settings')
            ->setDescription('general, admin, website, sms, payments, notifications, shipping')
            ->setNavigationItems([
                PageNavigationItem::make('General Settings')
                    ->translateLabel()
                    ->url(GeneralSettings::getUrl())
                    ->icon('heroicon-o-cog-6-tooth')
                    ->isActiveWhen(function () {
                        return request()->routeIs(GeneralSettings::getRouteName());
                    })
                    ->visible(true),
                PageNavigationItem::make('Admin Panel Settings')
                    ->translateLabel()
                    ->url(AdminPanelSettings::getUrl())
                    ->icon('heroicon-o-cog-6-tooth')
                    ->isActiveWhen(function () {
                        return request()->routeIs(AdminPanelSettings::getRouteName());
                    })
                    ->visible(true),
                PageNavigationItem::make('Web Settings')
                    ->translateLabel()
                    ->url(WebsiteSettings::getUrl())
                    ->icon('heroicon-o-cog-6-tooth')
                    ->isActiveWhen(function () {
                        return request()->routeIs(WebsiteSettings::getRouteName());
                    })
                    ->visible(true),
                PageNavigationItem::make('SMS Configuration')
                    ->translateLabel()
                    ->url(SmsConfiguration::getUrl())
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->isActiveWhen(function () {
                        return request()->routeIs(SmsConfiguration::getRouteName());
                    })
                    ->visible(true),
                PageNavigationItem::make('Notification Configuration')
                    ->translateLabel()
                    ->url(NotificationConfiguration::getUrl())
                    ->icon('heroicon-o-bell')
                    ->isActiveWhen(function () {
                        return request()->routeIs(NotificationConfiguration::getRouteName());
                    })
                    ->visible(true),
                PageNavigationItem::make('Payment Configuration')
                    ->translateLabel()
                    ->url(PaymentConfiguration::getUrl())
                    ->icon('heroicon-o-currency-dollar')
                    ->isActiveWhen(function () {
                        return request()->routeIs(PaymentConfiguration::getRouteName());
                    })
                    ->visible(true),
                PageNavigationItem::make('Shipping Configuration')
                    ->translateLabel()
                    ->url(ShippingConfiguration::getUrl())
                    ->icon('heroicon-o-truck')
                    ->isActiveWhen(function () {
                        return request()->routeIs(ShippingConfiguration::getRouteName());
                    })
                    ->visible(true),
            ]);
    }
}
