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

        // Helper Blade para Vite con fallback
        Blade::directive('viteSafe', function ($expression) {
            return "<?php try { echo app('Illuminate\\Foundation\\Vite')($expression); } catch (\\Illuminate\\Foundation\\ViteManifestNotFoundException \$e) { echo '<!-- Vite manifest not found, using fallback -->'; } ?>";
        });
    }
}

