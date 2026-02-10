<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oh no... 💔</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes cry {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(10px); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .cry-animation {
            animation: cry 1s ease-in-out infinite;
        }
        
        .fade-in {
            animation: fadeIn 1s ease-out;
        }
        
        .sad-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .tear {
            position: absolute;
            font-size: 20px;
            animation: fall 2s linear infinite;
        }
        
        @keyframes fall {
            to {
                transform: translateY(100vh);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="sad-bg min-h-screen flex items-center justify-center overflow-hidden">
    <div id="tears-container" class="fixed inset-0 pointer-events-none"></div>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-2xl p-8 md:p-12 text-center fade-in">
            <div class="text-9xl mb-6 cry-animation">😭</div>
            
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                Oh no... mi corazón está roto 💔
            </h1>
            
            <div class="space-y-4 text-gray-700 text-lg mb-8">
                <p>Pero entiendo... tal vez necesites pensarlo mejor</p>
                <p class="text-xl font-semibold text-pink-600">
                    Mi amor por ti seguirá siendo el mismo, siempre ❤️
                </p>
            </div>
            
            <div class="space-y-4">
                <a href="{{ route('valentine.index') }}" class="inline-block px-12 py-4 bg-gradient-to-r from-pink-500 to-red-500 text-white text-xl font-bold rounded-full shadow-lg hover:scale-105 transform transition-all duration-300">
                    Déjame intentarlo de nuevo 💕
                </a>
                
                <p class="text-sm text-gray-500 pt-4">
                    (Sabes que siempre puedes cambiar de opinión 😊)
                </p>
            </div>
            
            <div class="mt-8 text-6xl">
                <span class="cry-animation">❤️‍🩹</span>
            </div>
        </div>
    </div>

    <script>
        // Crear lágrimas cayendo
        function createTear() {
            const tearsContainer = document.getElementById('tears-container');
            const tear = document.createElement('div');
            tear.classList.add('tear');
            tear.textContent = '💧';
            tear.style.left = Math.random() * 100 + '%';
            tear.style.animationDuration = (Math.random() * 2 + 2) + 's';
            tear.style.animationDelay = Math.random() + 's';
            tearsContainer.appendChild(tear);
            
            setTimeout(() => {
                tear.remove();
            }, 4000);
        }
        
        setInterval(createTear, 500);
    </script>
</body>
</html>
