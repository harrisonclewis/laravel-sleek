<?php

namespace Harlew\Sleek;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class SleekServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/sleek.php', 'sleek'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/sleek.php' => config_path('sleek.php'),
        ], 'sleek-config');

        $this->registerSleekCompiler();
    }

    /**
     * Register the React-style component compiler with Blade.
     */
    protected function registerSleekCompiler(): void
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            // Register a precompiler that runs before standard Blade compilation
            $bladeCompiler->prepareStringsForCompilationUsing(function (string $value) {
                // Only transform if the feature is enabled
                if (!config('sleek.enabled', true)) {
                    return $value;
                }

                $ignoreTags = config('sleek.ignore_tags', []);
                $compiler = new SleekComponentCompiler($ignoreTags);
                return $compiler->compile($value);
            });
        });
    }
}