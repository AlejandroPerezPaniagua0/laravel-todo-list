<nav class="bg-white dark:bg-gray-800 shadow-lg mb-6 transition-colors duration-300">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 flex items-center gap-2 transition-colors duration-300">
                <i class="fas fa-tasks"></i>
                {{ __('app.app_name') }}
            </a>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('tasks.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition flex items-center gap-2">
                    <i class="fas fa-list"></i>
                    {{ __('app.tasks') }}
                </a>
                <a href="{{ route('settings.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition flex items-center gap-2">
                    <i class="fas fa-cog"></i>
                    {{ __('app.configuration') }}
                </a>
            </div>
        </div>
    </div>
</nav>
