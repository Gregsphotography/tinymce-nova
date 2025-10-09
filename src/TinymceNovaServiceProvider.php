<?php

declare(strict_types=1);

namespace Greg\TinymceNova;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class TinymceNovaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('tinymce-nova')
            ->hasAssets()
            ->hasViews()
            ->hasConfigFile();
    }

    public function boot(): void
    {
        parent::boot();

        Nova::serving(function (ServingNova $event) {
            Nova::script('tinymce-nova', __DIR__.'/../dist/js/field.js');
            Nova::style('tinymce-nova', __DIR__.'/../dist/css/field.css');
        });
    }
}
