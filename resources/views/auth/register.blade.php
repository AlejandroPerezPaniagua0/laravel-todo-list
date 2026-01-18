<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.register') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 min-h-screen flex items-center justify-center p-4 transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 w-full max-w-md transition-colors duration-300">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">{{ __('auth.create_account') }}</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ __('auth.register_subtitle') }}</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 mb-6 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('auth.full_name') }}
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    required
                    value="{{ old('name') }}"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="{{ __('auth.name_placeholder') }}"
                >
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('auth.email') }}
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    required
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="{{ __('auth.email_placeholder') }}"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('auth.password') }}
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required
                    minlength="8"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="{{ __('auth.password_min_8') }}"
                >
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('auth.password_requirement') }}</p>
            </div>

            <button 
                type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105"
            >
                {{ __('auth.create_account') }}
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('auth.already_have_account') }} 
                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-semibold">
                    {{ __('auth.login_here') }}
                </a>
            </p>
        </div>
    </div>

    <script>
        // Apply system theme by default
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Listen for changes in the system theme
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
</body>
</html>
