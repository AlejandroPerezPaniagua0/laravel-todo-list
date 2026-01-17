@extends('layout.app')

@section('title', 'Configuración')

@section('container-class', 'max-w-4xl')

@push('styles')
<style>
    .toggle-checkbox:checked {
        right: 0;
        border-color: #4F46E5;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #4F46E5;
    }
</style>
@endpush

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 dark:text-white transition-colors duration-300">
                <i class="fas fa-cog text-indigo-600 dark:text-indigo-400"></i> Configuración
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Personaliza tu experiencia</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tasks.index') }}" class="bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center gap-2">
                <i class="fas fa-tasks"></i>
                Mis Tareas
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-200 p-4 mb-6 rounded-lg shadow-md animate-pulse" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 transition-colors duration-300">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-3">
                <i class="fas fa-palette text-indigo-600 dark:text-indigo-400"></i>
                Apariencia
            </h2>

            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label class="text-gray-800 dark:text-white font-semibold text-lg flex items-center gap-2">
                            <i class="fas fa-moon text-indigo-500 dark:text-indigo-400"></i>
                            Tema
                        </label>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Selecciona el tema de la interfaz</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <select 
                            name="theme" 
                            id="theme"
                            onchange="previewTheme(this.value)"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white"
                        >
                            @foreach($themeOptions as $value => $label)
                                <option value="{{ $value }}" {{ $settings->theme === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label class="text-gray-800 dark:text-white font-semibold text-lg flex items-center gap-2">
                            <i class="fas fa-language text-indigo-500 dark:text-indigo-400"></i>
                            Idioma
                        </label>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Idioma de la aplicación</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <select 
                            name="language" 
                            id="language"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white"
                        >
                            @foreach($languageOptions as $value => $label)
                                <option value="{{ $value }}" {{ $settings->language === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 transition-colors duration-300">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-3">
                <i class="fas fa-bell text-indigo-600 dark:text-indigo-400"></i>
                Notificaciones
            </h2>

            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label class="text-gray-800 dark:text-white font-semibold text-lg flex items-center gap-2">
                            <i class="fas fa-envelope text-indigo-500 dark:text-indigo-400"></i>
                            Notificaciones por Email
                        </label>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Recibir notificaciones en tu correo</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <div class="relative">
                            <input 
                                type="checkbox" 
                                name="email_notifications" 
                                id="email_notifications"
                                value="1"
                                {{ $settings->email_notifications ? 'checked' : '' }}
                                class="sr-only toggle-checkbox"
                            >
                            <label for="email_notifications" class="block w-14 h-8 rounded-full bg-gray-300 dark:bg-gray-600 cursor-pointer toggle-label transition-colors duration-200"></label>
                            <span class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-200"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 transition-colors duration-300">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-3">
                <i class="fas fa-globe text-indigo-600 dark:text-indigo-400"></i>
                Regional
            </h2>

            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label class="text-gray-800 dark:text-white font-semibold text-lg flex items-center gap-2">
                            <i class="fas fa-clock text-indigo-500 dark:text-indigo-400"></i>
                            Zona Horaria
                        </label>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Configura tu zona horaria</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <select 
                            name="timezone" 
                            id="timezone"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white"
                        >
                            @foreach($timezoneOptions as $value => $label)
                                <option value="{{ $value }}" {{ $settings->timezone === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <label class="text-gray-800 dark:text-white font-semibold text-lg flex items-center gap-2">
                            <i class="fas fa-calendar text-indigo-500 dark:text-indigo-400"></i>
                            Formato de Fecha
                        </label>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Cómo se muestran las fechas</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <select 
                            name="date_format" 
                            id="date_format"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white"
                        >
                            @foreach($dateFormatOptions as $value => $label)
                                <option value="{{ $value }}" {{ $settings->date_format === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button 
                type="submit" 
                class="flex-1 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105 flex items-center justify-center gap-2"
            >
                <i class="fas fa-save"></i>
                Guardar Configuración
            </button>
        </div>
    </form>
        
    <div class="mt-4">
        <form action="{{ route('settings.reset') }}" method="POST" onsubmit="return confirm('¿Estás seguro de restaurar la configuración por defecto?')">
            @csrf
            <button 
                type="submit" 
                class="w-full bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center gap-2"
            >
                <i class="fas fa-undo"></i>
                Restaurar Valores por Defecto
            </button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Inicializar y animar los toggles
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-checkbox').forEach(checkbox => {
            const dot = checkbox.parentElement.querySelector('.dot');
            
            // Inicializar posición según estado del checkbox
            if (checkbox.checked) {
                dot.style.transform = 'translateX(24px)';
            } else {
                dot.style.transform = 'translateX(0)';
            }
            
            // Manejar cambios
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    dot.style.transform = 'translateX(24px)';
                } else {
                    dot.style.transform = 'translateX(0)';
                }
            });
        });
    });

    // Preview del tema en tiempo real
    function previewTheme(theme) {
        window.changeTheme(theme);
    }

    // Auto-ocultar mensaje de éxito después de 5 segundos
    setTimeout(function() {
        const successAlert = document.querySelector('[role="alert"]');
        if (successAlert) {
            successAlert.style.transition = 'opacity 0.5s';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 500);
        }
    }, 5000);
</script>
@endpush
