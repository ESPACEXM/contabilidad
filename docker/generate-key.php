<?php
/**
 * Script para generar APP_KEY sin cargar Laravel completo
 */

$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    echo "❌ Archivo .env no existe\n";
    exit(1);
}

$content = file_get_contents($envFile);

// Generar nueva clave
$key = 'base64:' . base64_encode(random_bytes(32));

// Reemplazar o agregar APP_KEY
if (preg_match('/^APP_KEY=.*$/m', $content)) {
    $content = preg_replace('/^APP_KEY=.*$/m', "APP_KEY={$key}", $content);
} else {
    $content = "APP_KEY={$key}\n" . $content;
}

file_put_contents($envFile, $content);
echo "✅ APP_KEY generada exitosamente\n";

