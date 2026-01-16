<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tareas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-800">
                    <i class="fas fa-tasks text-indigo-600"></i> Mis Tareas
                </h1>
                <p class="text-gray-600 mt-2">Bienvenido, <span class="font-semibold">{{ Auth::user()->name }}</span></p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>

        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md animate-pulse" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Formulario de nueva tarea -->
        <div class="bg-white rounded-xl shadow-xl p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-plus-circle text-indigo-600"></i>
                Crear Nueva Tarea
            </h2>
            <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                            Título <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            required
                            maxlength="150"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Ej: Completar informe mensual"
                            value="{{ old('title') }}"
                        >
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea 
                            name="content" 
                            id="content" 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Añade detalles sobre la tarea..."
                        >{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-indigo-600"></i> Fecha de Vencimiento
                        </label>
                        <input 
                            type="date" 
                            name="due_date" 
                            id="due_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            value="{{ old('due_date') }}"
                        >
                        @error('due_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-end">
                        <button 
                            type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-plus"></i>
                            Añadir Tarea
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Tareas</p>
                        <p class="text-3xl font-bold text-indigo-600">{{ $tasks->count() }}</p>
                    </div>
                    <i class="fas fa-list text-indigo-300 text-4xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Completadas</p>
                        <p class="text-3xl font-bold text-green-600">{{ $tasks->whereNotNull('completed_at')->count() }}</p>
                    </div>
                    <i class="fas fa-check-circle text-green-300 text-4xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Pendientes</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $tasks->whereNull('completed_at')->count() }}</p>
                    </div>
                    <i class="fas fa-clock text-yellow-300 text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Lista de tareas -->
        <div class="bg-white rounded-xl shadow-xl p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-indigo-600"></i>
                Lista de Tareas
            </h2>

            @if($tasks->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">No tienes tareas aún. ¡Crea tu primera tarea arriba!</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($tasks as $task)
                        @php
                            $isCompleted = !is_null($task->completed_at);
                            $isOverdue = !$isCompleted && $task->due_date && $task->due_date->isPast();
                            $isDueSoon = !$isCompleted && $task->due_date && $task->due_date->isToday();
                        @endphp
                        
                        <div class="border-l-4 {{ $isCompleted ? 'border-green-500 bg-green-50' : ($isOverdue ? 'border-red-500 bg-red-50' : ($isDueSoon ? 'border-yellow-500 bg-yellow-50' : 'border-indigo-500 bg-white')) }} rounded-lg shadow-md p-4 hover:shadow-lg transition duration-200">
                            <div class="flex items-start justify-between gap-4">
                                <!-- Información de la tarea -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <!-- Checkbox para completar -->
                                        <form action="{{ route('tasks.update', $task) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="title" value="{{ $task->title }}">
                                            <input type="hidden" name="content" value="{{ $task->content }}">
                                            <input type="hidden" name="due_date" value="{{ $task->due_date?->format('Y-m-d') }}">
                                            <input type="hidden" name="completed" value="{{ $isCompleted ? '0' : '1' }}">
                                            <button type="submit" class="text-2xl {{ $isCompleted ? 'text-green-500 hover:text-green-600' : 'text-gray-400 hover:text-green-500' }} transition">
                                                <i class="fas {{ $isCompleted ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <h3 class="text-lg font-semibold {{ $isCompleted ? 'line-through text-gray-500' : 'text-gray-800' }}">
                                            {{ $task->title }}
                                        </h3>
                                    </div>
                                    
                                    @if($task->content)
                                        <p class="text-gray-600 ml-11 mb-2">{{ $task->content }}</p>
                                    @endif
                                    
                                    <div class="flex flex-wrap items-center gap-3 ml-11 text-sm">
                                        @if($task->due_date)
                                            <span class="flex items-center gap-1 {{ $isOverdue ? 'text-red-600 font-semibold' : ($isDueSoon ? 'text-yellow-700 font-semibold' : 'text-gray-500') }}">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $task->due_date->format('d/m/Y') }}
                                                @if($isOverdue)
                                                    <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full ml-1">Vencida</span>
                                                @elseif($isDueSoon)
                                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full ml-1">Hoy</span>
                                                @endif
                                            </span>
                                        @endif
                                        
                                        @if($isCompleted)
                                            <span class="flex items-center gap-1 text-green-600">
                                                <i class="fas fa-check"></i>
                                                Completada el {{ $task->completed_at->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Acciones -->
                                <div class="flex items-center gap-2">
                                    <!-- Botón editar -->
                                    <button 
                                        onclick="openEditModal(this)"
                                        data-task-id="{{ $task->id }}"
                                        data-task-title="{{ $task->title }}"
                                        data-task-content="{{ $task->content ?? '' }}"
                                        data-task-due-date="{{ $task->due_date?->format('Y-m-d') ?? '' }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition duration-200 text-sm flex items-center gap-1"
                                    >
                                        <i class="fas fa-edit"></i>
                                        Editar
                                    </button>
                                    
                                    <!-- Botón eliminar -->
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta tarea?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition duration-200 text-sm flex items-center gap-1">
                                            <i class="fas fa-trash"></i>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de edición -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-edit text-indigo-600"></i>
                    Editar Tarea
                </h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="edit_title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="edit_title" 
                        required
                        maxlength="150"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    >
                </div>
                
                <div>
                    <label for="edit_content" class="block text-sm font-semibold text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea 
                        name="content" 
                        id="edit_content" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    ></textarea>
                </div>
                
                <div>
                    <label for="edit_due_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-indigo-600"></i> Fecha de Vencimiento
                    </label>
                    <input 
                        type="date" 
                        name="due_date" 
                        id="edit_due_date"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    >
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Guardar Cambios
                    </button>
                    <button 
                        type="button" 
                        onclick="closeEditModal()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200"
                    >
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(button) {
            const taskId = button.dataset.taskId;
            const title = button.dataset.taskTitle;
            const content = button.dataset.taskContent;
            const dueDate = button.dataset.taskDueDate;
            
            document.getElementById('editForm').action = `/task/${taskId}`;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_content').value = content;
            document.getElementById('edit_due_date').value = dueDate;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera de él
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Cerrar modal con la tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });

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
</body>
</html>
