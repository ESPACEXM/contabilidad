<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());

        // Forzar HTTPS en producción para todas las URLs
        if ($this->app->environment('production') || request()->secure()) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            // También forzar en la configuración de la app
            if (config('app.url') && str_starts_with(config('app.url'), 'http://')) {
                config(['app.url' => str_replace('http://', 'https://', config('app.url'))]);
            }
        }

        // Helper Blade para Vite con fallback
        Blade::directive('viteSafe', function ($expression) {
            return "<?php try { echo app('Illuminate\\Foundation\\Vite')($expression); } catch (\\Illuminate\\Foundation\\ViteManifestNotFoundException \$e) { echo '<!-- Vite manifest not found, using fallback -->'; } ?>";
        });
    }
}

