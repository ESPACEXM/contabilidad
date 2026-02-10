<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Sí dijiste que sí! 🎉💕</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes confetti-fall {
            to {
                transform: translateY(100vh) rotate(360deg);
            }
        }
        
        @keyframes bounce-in {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        @keyframes pulse-big {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
        }
        
        @keyframes rotate-heart {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .confetti {
            position: absolute;
            width: 8px;
            height: 8px;
            animation: confetti-fall linear infinite;
        }
        
        @media (min-width: 640px) {
            .confetti {
                width: 10px;
                height: 10px;
            }
        }
        
        @media (min-width: 1024px) {
            .confetti {
                width: 12px;
                height: 12px;
            }
        }
        
        .bounce-in {
            animation: bounce-in 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        .pulse-big {
            animation: pulse-big 1s ease-in-out infinite;
        }
        
        .rotate-heart {
            animation: rotate-heart 3s linear infinite;
        }
        
        .celebration-bg {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
    </style>
</head>
<body class="celebration-bg min-h-screen flex items-center justify-center overflow-hidden">
    <div id="confetti-container" class="fixed inset-0 pointer-events-none"></div>
    
    <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
        <div class="max-w-3xl mx-auto text-center space-y-4 sm:space-y-8">
            <!-- Celebración principal -->
            <div class="bounce-in">
                <div class="text-6xl sm:text-7xl md:text-9xl mb-4 sm:mb-6 pulse-big">🎉</div>
                <h1 class="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-bold text-white drop-shadow-2xl mb-4 sm:mb-8 px-2">
                    ¡DIJISTE QUE SÍÍÍÍ!
                </h1>
            </div>
            
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl p-4 sm:p-8 md:p-12 bounce-in" style="animation-delay: 0.2s;">
                <div class="text-5xl sm:text-6xl md:text-8xl mb-4 sm:mb-6 rotate-heart">💖</div>
                
                <div class="space-y-4 sm:space-y-6 px-2">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-red-500">
                        ¡Eres la mejor San Valentín del mundo!
                    </h2>
                    
                    <div class="text-base sm:text-xl md:text-2xl text-gray-700 space-y-3 sm:space-y-4">
                        <p>¡Me haces la persona más feliz del universo! 🌟</p>
                        <p>No puedo esperar para celebrar este día tan especial contigo</p>
                        <p class="font-bold text-pink-600">¡Te amo con todo mi corazón! 💕</p>
                    </div>
                    
                    <div class="flex justify-center space-x-2 sm:space-x-4 text-4xl sm:text-5xl md:text-6xl pt-4 sm:pt-6">
                        <span class="pulse-big">❤️</span>
                        <span class="pulse-big" style="animation-delay: 0.2s;">💕</span>
                        <span class="pulse-big" style="animation-delay: 0.4s;">💖</span>
                        <span class="pulse-big" style="animation-delay: 0.6s;">💗</span>
                    </div>
                    
                    <div class="pt-4 sm:pt-8 text-gray-600">
                        <p class="text-sm sm:text-base md:text-lg mb-3 sm:mb-4">Este es solo el comienzo de una celebración increíble...</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-bold text-red-500">¡Prepárate para muchas sorpresas! 🎁</p>
                    </div>
                </div>
            </div>
            
            <div class="text-white text-base sm:text-xl md:text-2xl font-bold drop-shadow-lg bounce-in px-3" style="animation-delay: 0.4s;">
                <p>"Cada momento contigo es mi San Valentín perfecto" 💑</p>
            </div>
            
            <div class="bounce-in px-2" style="animation-delay: 0.6s;">
                <a href="{{ route('valentine.index') }}" class="inline-block px-6 sm:px-8 py-3 sm:py-4 bg-white text-pink-600 text-base sm:text-lg font-bold rounded-full shadow-lg hover:bg-pink-50 transform hover:scale-105 transition-all duration-300">
                    💝 Volver al inicio
                </a>
            </div>
        </div>
    </div>

    <script>
        // Crear confetti
        const colors = ['#ff6b9d', '#c44569', '#f8b500', '#786fa6', '#f19066', '#ea8685'];
        
        function createConfetti() {
            const container = document.getElementById('confetti-container');
            const confetti = document.createElement('div');
            confetti.classList.add('confetti');
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
            confetti.style.animationDelay = Math.random() * 2 + 's';
            container.appendChild(confetti);
            
            setTimeout(() => {
                confetti.remove();
            }, 5000);
        }
        
        // Crear confetti continuamente
        const confettiInterval = window.innerWidth < 640 ? 200 : 100;
        setInterval(createConfetti, confettiInterval);
        
        // Crear muchos confetti al inicio (menos en móviles)
        const initialConfetti = window.innerWidth < 640 ? 30 : 50;
        for (let i = 0; i < initialConfetti; i++) {
            setTimeout(createConfetti, i * 50);
        }
        
        // Fireworks de emojis
        function createFirework() {
            const emojis = ['❤️', '💕', '💖', '💗', '💓', '💝', '🎉', '✨', '⭐', '🌟'];
            const x = Math.random() * window.innerWidth;
            const y = Math.random() * window.innerHeight * 0.5;
            
            // Menos partículas en móviles
            const particleCount = window.innerWidth < 640 ? 6 : 8;
            
            for (let i = 0; i < particleCount; i++) {
                const emoji = document.createElement('div');
                emoji.textContent = emojis[Math.floor(Math.random() * emojis.length)];
                emoji.style.position = 'fixed';
                emoji.style.left = x + 'px';
                emoji.style.top = y + 'px';
                emoji.style.fontSize = window.innerWidth < 640 ? '18px' : '24px';
                emoji.style.pointerEvents = 'none';
                emoji.style.zIndex = '1000';
                document.body.appendChild(emoji);
                
                const angle = (Math.PI * 2 * i) / particleCount;
                const velocity = window.innerWidth < 640 ? 2 : 3;
                const vx = Math.cos(angle) * velocity;
                const vy = Math.sin(angle) * velocity;
                
                let posX = x;
                let posY = y;
                let opacity = 1;
                
                const animate = () => {
                    posX += vx;
                    posY += vy;
                    opacity -= 0.02;
                    
                    emoji.style.left = posX + 'px';
                    emoji.style.top = posY + 'px';
                    emoji.style.opacity = opacity;
                    
                    if (opacity > 0) {
                        requestAnimationFrame(animate);
                    } else {
                        emoji.remove();
                    }
                };
                
                animate();
            }
        }
        
        setInterval(createFirework, 1000);
    </script>
</body>
</html>
