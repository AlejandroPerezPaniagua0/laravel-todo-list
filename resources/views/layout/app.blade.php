<!DOCTYPE html>
<html lang="es" class="{{ Auth::check() ? Auth::user()->getTheme() : 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Todo App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 min-h-screen transition-colors duration-300">
    @auth
        @include('layout.navbar')
    @endauth

    <div class="container mx-auto px-4 py-8 @yield('container-class', 'max-w-6xl')">
        @yield('content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const theme = "{{ Auth::check() ? Auth::user()->getTheme() : 'light' }}";
            applyTheme(theme);
        });

        function applyTheme(theme) {
            const html = document.documentElement;
            if (theme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }

        window.changeTheme = function(theme) {
            applyTheme(theme);
        }
    </script>

    @stack('scripts')
</body>
</html>
