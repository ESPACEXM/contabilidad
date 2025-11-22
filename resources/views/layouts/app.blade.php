<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema Contabilidad') - {{ config('app.name', 'Laravel') }}</title>
    @php
        // Función helper para forzar HTTPS en producción
        $forceHttps = function($url) {
            // Si la URL ya es absoluta y es HTTP, cambiarla a HTTPS
            if (substr($url, 0, 7) === 'http://') {
                return str_replace('http://', 'https://', $url);
            }
            // Si es relativa, usar secure_asset() o url() con HTTPS
            if (substr($url, 0, 1) === '/') {
                $baseUrl = config('app.url', 'https://' . request()->getHost());
                // Asegurar que baseUrl use HTTPS
                if (substr($baseUrl, 0, 7) === 'http://') {
                    $baseUrl = str_replace('http://', 'https://', $baseUrl);
                }
                return rtrim($baseUrl, '/') . $url;
            }
            return $url;
        };
        
        // Intentar usar el helper de Vite de Laravel primero (más confiable)
        try {
            $vite = app('Illuminate\Foundation\Vite');
            echo $vite(['resources/css/app.css', 'resources/js/app.js']);
        } catch (\Exception $e) {
            // Si falla, leer el manifest manualmente
            $viteManifest = public_path('build/manifest.json');
            if (file_exists($viteManifest)) {
                try {
                    $manifestContent = file_get_contents($viteManifest);
                    $manifest = json_decode($manifestContent, true);
                    
                    if ($manifest && is_array($manifest)) {
                        // CSS - usar secure_asset() o forzar HTTPS
                        if (isset($manifest['resources/css/app.css']['file'])) {
                            $cssFile = $manifest['resources/css/app.css']['file'];
                            $cssUrl = secure_asset('build/' . $cssFile);
                            // Asegurar HTTPS
                            $cssUrl = $forceHttps($cssUrl);
                            echo '<link rel="stylesheet" href="' . htmlspecialchars($cssUrl) . '">' . "\n";
                        }
                        
                        // JS
                        if (isset($manifest['resources/js/app.js']['file'])) {
                            $jsFile = $manifest['resources/js/app.js']['file'];
                            $jsUrl = secure_asset('build/' . $jsFile);
                            // Asegurar HTTPS
                            $jsUrl = $forceHttps($jsUrl);
                            echo '<script type="module" src="' . htmlspecialchars($jsUrl) . '"></script>' . "\n";
                        }
                    } else {
                        throw new \Exception('Manifest inválido');
                    }
                } catch (\Exception $e2) {
                    // Fallback final: CDN
                    echo '<!-- Error cargando assets: ' . htmlspecialchars($e2->getMessage()) . ' -->' . "\n";
                    echo '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>' . "\n";
                    echo '<style>[x-cloak]{display:none!important}</style>' . "\n";
                }
            } else {
                // No existe manifest
                echo '<!-- Manifest no encontrado en: ' . htmlspecialchars($viteManifest) . ' -->' . "\n";
                echo '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>' . "\n";
                echo '<style>[x-cloak]{display:none!important}</style>' . "\n";
            }
        }
    @endphp
    @livewireStyles
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    @auth
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg fixed h-full">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Sistema Contabilidad</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->tenant->name }}</p>
                </div>
                <nav class="p-4 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 120px);">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contabilidad</p>
                    </div>
                    
                    <a href="{{ route('chart-accounts.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('chart-accounts.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Catálogo de Cuentas
                    </a>
                    <a href="{{ route('journal-entries.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('journal-entries.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Pólizas Contables
                    </a>
                    <a href="{{ route('general-ledger.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('general-ledger.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Libro Mayor
                    </a>
                    
                    <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estados Financieros</p>
                    </div>
                    
                    <a href="{{ route('financial-statements.balance-sheet') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('financial-statements.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Estados Financieros
                    </a>
                    
                    <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inventario</p>
                    </div>
                    
                    <a href="{{ route('products.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('products.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Productos
                    </a>
                    <a href="{{ route('inventory.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('inventory.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Movimientos Inventario
                    </a>
                    
                    <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Presupuestos</p>
                    </div>
                    
                    <a href="{{ route('budgets.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('budgets.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Presupuestos
                    </a>
                    
                    <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Análisis</p>
                    </div>
                    
                    <a href="{{ route('financial-analysis.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('financial-analysis.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Análisis Financiero (VAN, TIR, Punto Equilibrio)
                    </a>
                    
                    @role('super-admin')
                    <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Administración</p>
                    </div>
                    <a href="{{ route('tenants.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('tenants.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Empresas
                    </a>
                    @endrole
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 ml-64">
                <!-- Header -->
                <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-10">
                    <div class="px-6 py-4 flex justify-between items-center">
                        <div>
                            @yield('breadcrumb')
                        </div>
                        <div class="flex items-center space-x-4">
                            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </button>
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2">
                                    <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Cerrar Sesión</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Content -->
                <main class="p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    @livewireScripts
</body>
</html>

