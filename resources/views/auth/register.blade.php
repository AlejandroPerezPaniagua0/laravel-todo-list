<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Crear Cuenta</h1>
            <p class="text-gray-600">Regístrate para comenzar a gestionar tus tareas</p>
        </div>

        <!-- Errores -->
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf
            
            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nombre Completo
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    required
                    value="{{ old('name') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="Juan Pérez"
                >
            </div>

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
                    minlength="8"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="Mínimo 8 caracteres"
                >
                <p class="text-xs text-gray-500 mt-1">La contraseña debe tener al menos 8 caracteres</p>
            </div>

            <!-- Botón de envío -->
            <button 
                type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105"
            >
                Crear Cuenta
            </button>
        </form>

        <!-- Link a login -->
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                ¿Ya tienes cuenta? 
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </div>
</body>
</html>
