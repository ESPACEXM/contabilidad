# 🚀 Script de prueba rápida

Write-Host "💕 Iniciando proyecto de San Valentín..." -ForegroundColor Magenta

# Verificar si composer está instalado
if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Composer no está instalado. Por favor instala Composer primero." -ForegroundColor Red
    exit 1
}

Write-Host "✅ Composer encontrado" -ForegroundColor Green

# Verificar si .env existe
if (!(Test-Path ".env")) {
    Write-Host "⚠️  .env no existe. Copiando desde .env.example..." -ForegroundColor Yellow
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env"
        Write-Host "✅ .env creado" -ForegroundColor Green
    } else {
        Write-Host "❌ .env.example no encontrado" -ForegroundColor Red
    }
}

# Verificar vendor
if (!(Test-Path "vendor")) {
    Write-Host "📦 Instalando dependencias de Composer..." -ForegroundColor Cyan
    composer install --no-interaction
    Write-Host "✅ Dependencias instaladas" -ForegroundColor Green
} else {
    Write-Host "✅ Dependencias ya instaladas" -ForegroundColor Green
}

# Generar key si no existe
$envContent = Get-Content ".env" -Raw
if ($envContent -notmatch "APP_KEY=base64:") {
    Write-Host "🔑 Generando APP_KEY..." -ForegroundColor Cyan
    php artisan key:generate
    Write-Host "✅ APP_KEY generada" -ForegroundColor Green
} else {
    Write-Host "✅ APP_KEY ya existe" -ForegroundColor Green
}

Write-Host ""
Write-Host "💝 ¡Todo listo! Ahora ejecuta:" -ForegroundColor Magenta
Write-Host "   php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "📱 Luego abre: http://localhost:8000" -ForegroundColor Cyan
Write-Host ""
Write-Host "¡Buena suerte con tu novia! 💕" -ForegroundColor Magenta
