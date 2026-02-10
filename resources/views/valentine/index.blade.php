<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>¿Quieres ser mi San Valentín? 💕</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
            75% { transform: scale(1.05); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .heartbeat {
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        
        .fade-in {
            animation: fadeIn 1s ease-out forwards;
        }
        
        .heart-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .falling-hearts {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .heart {
            position: absolute;
            font-size: 20px;
            animation: fall linear infinite;
        }
        
        @keyframes fall {
            to {
                transform: translateY(100vh) rotate(360deg);
            }
        }
        
        .no-button {
            transition: all 0.3s ease;
        }
        
        .no-button:hover {
            transform: scale(0.9);
        }
    </style>
</head>
<body class="heart-bg min-h-screen flex items-center justify-center overflow-hidden">
    <div class="falling-hearts" id="falling-hearts"></div>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-2xl p-8 md:p-12 relative overflow-hidden fade-in">
            <!-- Decoración de corazones -->
            <div class="absolute top-4 right-4 text-6xl heartbeat">💖</div>
            <div class="absolute bottom-4 left-4 text-4xl float-animation" style="animation-delay: 0.5s;">💕</div>
            
            <div class="text-center space-y-8">
                <!-- Título principal -->
                <div class="space-y-4 fade-in" style="animation-delay: 0.2s;">
                    <h1 class="text-5xl md:text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 via-red-500 to-purple-500 mb-4">
                        ¡Hola mi amor! 💝
                    </h1>
                    <div class="text-8xl heartbeat">❤️</div>
                </div>
                
                <!-- Mensaje romántico -->
                <div class="space-y-4 text-gray-700 text-lg md:text-xl fade-in" style="animation-delay: 0.4s;">
                    <p class="leading-relaxed">
                        Cada día a tu lado es un regalo especial,
                        y este San Valentín quiero que sea inolvidable...
                    </p>
                    <p class="text-2xl md:text-3xl font-bold text-pink-600 my-6">
                        ¿Quieres ser mi San Valentín? 💘
                    </p>
                    <p class="text-gray-600">
                        Prometo llenarte de amor, risas y momentos mágicos
                    </p>
                </div>
                
                <!-- Botones -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-6 fade-in" style="animation-delay: 0.6s;">
                    <form action="{{ route('valentine.answer') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <input type="hidden" name="answer" value="si">
                        <button type="submit" class="w-full sm:w-auto px-12 py-4 bg-gradient-to-r from-pink-500 to-red-500 text-white text-xl font-bold rounded-full shadow-lg transform transition-all duration-300 hover:scale-110 hover:shadow-xl hover:from-pink-600 hover:to-red-600">
                            ¡Sí, mi amor! 💕
                        </button>
                    </form>
                    
                    <button id="no-button" class="no-button w-full sm:w-auto px-12 py-4 bg-gray-300 text-gray-700 text-xl font-bold rounded-full shadow-lg">
                        No... 😢
                    </button>
                </div>
                
                <!-- Mensaje adicional -->
                <div class="pt-6 text-sm text-gray-500 fade-in" style="animation-delay: 0.8s;">
                    <p>💌 Con todo mi amor, siempre y para siempre 💌</p>
                </div>
            </div>
        </div>
        
        <!-- Frase romántica en la parte inferior -->
        <div class="text-center mt-8 fade-in" style="animation-delay: 1s;">
            <p class="text-white text-lg md:text-xl font-semibold drop-shadow-lg">
                "En cada latido de mi corazón, está tu nombre" 💗
            </p>
        </div>
    </div>

    <script>
        // Crear corazones cayendo
        function createFallingHeart() {
            const heartsContainer = document.getElementById('falling-hearts');
            const heart = document.createElement('div');
            heart.classList.add('heart');
            heart.textContent = ['❤️', '💕', '💖', '💗', '💓', '💝'][Math.floor(Math.random() * 6)];
            heart.style.left = Math.random() * 100 + '%';
            heart.style.animationDuration = (Math.random() * 3 + 3) + 's';
            heart.style.opacity = Math.random() * 0.5 + 0.3;
            heartsContainer.appendChild(heart);
            
            setTimeout(() => {
                heart.remove();
            }, 6000);
        }
        
        setInterval(createFallingHeart, 300);
        
        // Comportamiento del botón "No"
        const noButton = document.getElementById('no-button');
        let clickCount = 0;
        const messages = [
            'No... 😢',
            '¿Estás segura? 🥺',
            'Por favor... 🙏',
            'Piénsalo mejor 💔',
            'Te amo... 😭',
            'Solo di que sí 💘'
        ];
        
        noButton.addEventListener('click', function() {
            clickCount++;
            
            if (clickCount < messages.length) {
                noButton.textContent = messages[clickCount];
                noButton.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    noButton.style.transform = 'scale(1)';
                }, 200);
            } else {
                // Después de varios clics, el botón se mueve
                const x = Math.random() * (window.innerWidth - noButton.offsetWidth - 100);
                const y = Math.random() * (window.innerHeight - noButton.offsetHeight - 100);
                noButton.style.position = 'fixed';
                noButton.style.left = x + 'px';
                noButton.style.top = y + 'px';
            }
        });
    </script>
</body>
</html>
