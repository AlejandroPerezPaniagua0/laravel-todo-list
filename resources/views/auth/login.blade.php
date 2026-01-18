<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.login') }}</title>
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
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">{{ __('auth.welcome') }}</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ __('auth.login_subtitle') }}</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 mb-6 rounded-lg">
                <p class="font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            
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
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="••••••••"
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105"
            >
                {{ __('auth.login') }}
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('auth.dont_have_account') }} 
                <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-semibold">
                    {{ __('auth.register_here') }}
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
