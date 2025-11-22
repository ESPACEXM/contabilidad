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

        // Forzar HTTPS en producción para todas las URLs (ejecutar muy temprano)
        if ($this->app->environment('production') || 
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            
            // También forzar en la configuración de la app
            $appUrl = config('app.url');
            if ($appUrl && substr($appUrl, 0, 7) === 'http://') {
                config(['app.url' => str_replace('http://', 'https://', $appUrl)]);
            }
        }

        // Helper Blade para Vite con fallback
        Blade::directive('viteSafe', function ($expression) {
            return "<?php try { echo app('Illuminate\\Foundation\\Vite')($expression); } catch (\\Illuminate\\Foundation\\ViteManifestNotFoundException \$e) { echo '<!-- Vite manifest not found, using fallback -->'; } ?>";
        });
    }
}

