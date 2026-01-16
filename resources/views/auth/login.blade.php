<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Bienvenido</h1>
            <p class="text-gray-600">Inicia sesión para gestionar tus tareas</p>
        </div>

        <!-- Errores -->
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <p class="font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        <!-- Formulario -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Correo Electrónico
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    required
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="tu@email.com"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Contraseña
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="••••••••"
                >
            </div>

            <!-- Botón de envío -->
            <button 
                type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105"
            >
                Iniciar Sesión
            </button>
        </form>

        <!-- Link a registro -->
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                    Regístrate aquí
                </a>
            </p>
        </div>
    </div>
</body>
</html>
