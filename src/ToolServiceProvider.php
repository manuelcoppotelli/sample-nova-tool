<?php

namespace PsiStudio\SampleTool;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use PsiStudio\SampleTool\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Tool identifier name.
     *
     * @var string
     */
    public static $name = 'sample-tool';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', static::$name);
        $this->loadViewsFrom(__DIR__.'/../resources/views', static::$name);

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::translations(static::getTranslations());
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('api/'.static::$name)
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the translation keys from file.
     *
     * @return array
     */
    private static function getTranslations(): array
    {
        $translationFile = resource_path('lang/vendor/'.static::$name.'/'.app()->getLocale().'.json');

        if (!is_readable($translationFile)) {
            $translationFile = __DIR__.'/../resources/lang/'.app()->getLocale().'.json';

            if (!is_readable($translationFile)) {
                return [];
            }
        }

        return json_decode(file_get_contents($translationFile), true);
    }
}
