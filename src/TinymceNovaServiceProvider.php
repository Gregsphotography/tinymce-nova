<?php

declare(strict_types=1);

namespace Greg\TinymceNova;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

final class TinymceNovaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish config file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/tinymce-nova.php' => config_path('tinymce-nova.php'),
            ], 'config');
        }

        // Register Nova assets
        Nova::serving(function (ServingNova $event) {
            Nova::script('tinymce-nova', __DIR__.'/../dist/js/field.js');
            Nova::style('tinymce-nova', __DIR__.'/../dist/css/field.css');
        });
    }

    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/../config/tinymce-nova.php', 'tinymce-nova');
    }
}
