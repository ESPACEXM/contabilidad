<?php
/**
 * Script para limpiar referencias a dependencias de desarrollo del cache de servicios
 */

$servicesFile = __DIR__ . '/../bootstrap/cache/services.php';

if (file_exists($servicesFile)) {
    $content = file_get_contents($servicesFile);
    
    // Remover referencias a Collision (dependencia de desarrollo)
    $content = preg_replace(
        "/'NunoMaduro\\\\Collision\\\\Adapters\\\\Laravel\\\\CollisionServiceProvider',?\s*/",
        '',
        $content
    );
    
    // Remover referencias a Termwind (dependencia de desarrollo)
    $content = preg_replace(
        "/'Termwind\\\\Laravel\\\\TermwindServiceProvider',?\s*/",
        '',
        $content
    );
    
    // Remover referencias a Laravel Sail (dependencia de desarrollo)
    $content = preg_replace(
        "/'Laravel\\\\Sail\\\\SailServiceProvider',?\s*/",
        '',
        $content
    );
    
    // Remover referencias a Laravel Tinker si no está instalado
    if (!class_exists('Laravel\Tinker\TinkerServiceProvider')) {
        $content = preg_replace(
            "/'Laravel\\\\Tinker\\\\TinkerServiceProvider',?\s*/",
            '',
            $content
        );
    }
    
    file_put_contents($servicesFile, $content);
    echo "✅ Cache de servicios limpiado\n";
} else {
    echo "ℹ️  Cache de servicios no existe, se creará automáticamente\n";
}

