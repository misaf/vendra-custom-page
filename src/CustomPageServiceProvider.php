<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage;

use Filament\Panel;
use Illuminate\Foundation\Console\AboutCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class CustomPageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('vendra-custom-page')
            ->hasTranslations()
            ->hasMigrations([
                'create_custom_pages_table'
            ])
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command->askToStarRepoOnGitHub('misaf/vendra-custom-page');
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            if ('admin' !== $panel->getId()) {
                return;
            }

            $panel->plugin(CustomPagePlugin::make());
        });
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Custom Page', fn() => ['Version' => 'dev-master']);
    }
}
