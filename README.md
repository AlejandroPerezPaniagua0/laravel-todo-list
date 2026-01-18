# 📝 Laravel Todo List

A clean and simple task management application built with **Laravel 12** following **MVC architecture** and **KISS principles** (Keep It Simple, Stupid). This portfolio project demonstrates modern PHP development with automated email notifications and multi-language support.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.0-06B6D4?style=flat&logo=tailwindcss)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

> **Portfolio Project** by [Alejandro Pérez Paniagua](#-author)

[🇪🇸 Spanish Version](#-versión-en-español)

---

## 🎯 Why This Project?

This application showcases:
- **MVC Pattern**: Clean separation between Models, Views, and Controllers
- **KISS Principle**: Simple, maintainable code without over-engineering
- **Laravel Best Practices**: Eloquent ORM, Blade templates, middleware, scheduled tasks
- **Real-world Features**: Authentication, email notifications, multi-language support
- **Production Ready**: Security features, soft deletes, CSRF protection

---

## ✨ Key Features

- ✅ **Task Management** - Create, read, update, delete tasks
- 📅 **Due Dates** - Set deadlines and track completion
- 📧 **Email Notifications** - Automatic reminders for overdue tasks
- 🌍 **Multi-language** - English and Spanish support
- ⚙️ **User Settings** - Personalize theme, language, and notifications
- 🔐 **Authentication** - Secure login and registration

---

## 🛠️ Tech Stack

**Backend**
- Laravel 12.x (MVC Framework)
- PHP 8.2+
- MySQL/PostgreSQL/SQLite
- Eloquent ORM

**Frontend**
- Blade Templates
- Tailwind CSS 4.0
- Vite

**Key Concepts**
- MVC Architecture
- KISS Principle
- RESTful routing
- Middleware pattern
- Task scheduling
- Mailable classes

---

## 🚀 Quick Start

```bash
# Clone repository
git clone https://github.com/alejandro-perez-paniagua/laravel-todo-list.git
cd laravel-todo-list

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Build assets
npm run build

# Start server
php artisan serve
```

Visit `http://localhost:8000`

---

## 📁 MVC Structure

Following Laravel's MVC pattern:

```
app/
├── Models/                    # Data layer (Eloquent ORM)
│   ├── User.php
│   ├── Task.php
│   ├── TaskReminder.php
│   └── UserSetting.php
│
├── Controllers/               # Business logic layer
│   ├── TaskController.php     # CRUD operations
│   ├── AuthController.php     # Authentication
│   └── UserSettingsController.php
│
└── Views/                     # Presentation layer (Blade)
    ├── tasks/
    ├── auth/
    └── configuration/
```

### KISS Principle in Action

**Simple Reminder System:**
- Task created with `due_date` → Create reminder
- Scheduler checks every 5 minutes → Send email if overdue
- User completes task → Remove reminder

**No over-engineering:** No complex queue systems, no microservices, no unnecessary abstractions. Just clean, maintainable Laravel code.

---

## ⚙️ Configuration

### Email Setup

Edit `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@todoapp.com"
```

### Task Scheduler

For automated email notifications, add to crontab:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Development:** Run `php artisan schedule:work`

---

## 🗄️ Database Design

**Simple relational structure:**

```
User (1) ──── (N) Task
  │              │
  │              └── (N) TaskReminder
  │
  └── (1) UserSetting
```

**4 Main Tables:**
- `users` - Authentication
- `tasks` - Todo items (with soft deletes)
- `task_reminders` - Email notification tracking
- `user_settings` - User preferences

---

## 📚 Core Functionality

### Task Management (MVC Example)

**Model** (Task.php)
```php
class Task extends Model
{
    use SoftDeletes;
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function taskReminder() {
        return $this->hasMany(TaskReminder::class);
    }
}
```

**Controller** (TaskController.php)
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:150',
        'due_date' => 'nullable|date',
    ]);
    
    $task = Task::create([
        'user_id' => Auth::id(),
        'title' => $validated['title'],
        'due_date' => $validated['due_date'],
    ]);
    
    // KISS: Simple reminder creation
    if ($task->due_date) {
        TaskReminder::create([
            'task_id' => $task->id,
            'remind_at' => $task->due_date,
        ]);
    }
    
    return redirect()->back();
}
```

**View** (tasks/index.blade.php)
```blade
@foreach($tasks as $task)
    <div>
        <h3>{{ $task->title }}</h3>
        <p>{{ __('tasks.due_date') }}: {{ $task->due_date }}</p>
    </div>
@endforeach
```

### Email Notifications

**Scheduled Command** (runs every 5 minutes)
```bash
php artisan tasks:process-notifications
```

**What it does:**
1. Find tasks where `due_date` <= now and not completed
2. Check if user has `email_notifications` enabled
3. Send localized email (EN/ES based on user settings)
4. Mark reminder as sent

**KISS approach:** One command does everything. No complex queues, no event system, just straightforward logic.

---

## 🌍 Multi-language Support

**Middleware automatically detects user language:**

```php
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->user()?->settings?->language ?? 'en';
        app()->setLocale($locale);
        return $next($request);
    }
}
```

**Translation files:**
```
lang/
├── en/
│   └── tasks.php, emails.php, settings.php
└── es/
    └── tasks.php, emails.php, settings.php
```

**Usage:**
```blade
{{ __('tasks.title') }}  // "My Tasks" or "Mis Tareas"
```

---

## 🧪 Testing

### Manual Test

**Test email system:**
```bash
php artisan tinker
```

```php
Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test from Laravel');
});
```

**Test notifications:**
```bash
# Run command manually
php artisan tasks:process-notifications -v
```

---

## 🔒 Security Features

- ✅ CSRF protection on forms
- ✅ Password hashing (bcrypt)
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS prevention (Blade auto-escaping)
- ✅ Mass assignment protection
- ✅ Authentication middleware

---

## 📄 License

MIT License - feel free to use this project for learning or portfolio purposes.

---

## 👤 Author

**Alejandro Pérez Paniagua**

Full-Stack Developer | Laravel Specialist

- 🌐 Portfolio: [Coming soon](#)
- 💼 LinkedIn: [linkedin.com/in/alejandro-perez-paniagua](#)
- 🐙 GitHub: [@alejandro-perez-paniagua](https://github.com/alejandro-perez-paniagua)
- 📧 Email: [contact@alejandro-perez.dev](#)

> *This project demonstrates my ability to build clean, maintainable web applications following industry best practices (MVC, KISS, PSR-12) with modern PHP and Laravel.*

---

## 🙏 Acknowledgments

Built with [Laravel](https://laravel.com) and [Tailwind CSS](https://tailwindcss.com)

---

<br>
<br>

# 🇪🇸 Versión en Español

---

# 📝 Laravel Todo List

Una aplicación limpia y simple de gestión de tareas construida con **Laravel 12** siguiendo la **arquitectura MVC** y los **principios KISS** (Keep It Simple, Stupid). Este proyecto de portfolio demuestra desarrollo PHP moderno con notificaciones automáticas por email y soporte multi-idioma.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.0-06B6D4?style=flat&logo=tailwindcss)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

> **Proyecto Portfolio** de [Alejandro Pérez Paniagua](#-autor)

---

## 🎯 ¿Por Qué Este Proyecto?

Esta aplicación demuestra:
- **Patrón MVC**: Separación limpia entre Modelos, Vistas y Controladores
- **Principio KISS**: Código simple y mantenible sin sobre-ingeniería
- **Mejores Prácticas Laravel**: Eloquent ORM, Blade templates, middleware, tareas programadas
- **Características Reales**: Autenticación, notificaciones por email, soporte multi-idioma
- **Listo para Producción**: Características de seguridad, soft deletes, protección CSRF

---

## ✨ Características Principales

- ✅ **Gestión de Tareas** - Crear, leer, actualizar, eliminar tareas
- 📅 **Fechas de Vencimiento** - Establecer plazos y seguir completado
- 📧 **Notificaciones Email** - Recordatorios automáticos para tareas vencidas
- 🌍 **Multi-idioma** - Soporte inglés y español
- ⚙️ **Configuración Usuario** - Personalizar tema, idioma y notificaciones
- 🔐 **Autenticación** - Login y registro seguros

---

## 🛠️ Stack Tecnológico

**Backend**
- Laravel 12.x (Framework MVC)
- PHP 8.2+
- MySQL/PostgreSQL/SQLite
- Eloquent ORM

**Frontend**
- Blade Templates
- Tailwind CSS 4.0
- Vite

**Conceptos Clave**
- Arquitectura MVC
- Principio KISS
- Enrutamiento RESTful
- Patrón Middleware
- Programación de tareas
- Clases Mailable

---

## 🚀 Inicio Rápido

```bash
# Clonar repositorio
git clone https://github.com/alejandro-perez-paniagua/laravel-todo-list.git
cd laravel-todo-list

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
DB_DATABASE=tu_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Ejecutar migraciones
php artisan migrate

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

Visita `http://localhost:8000`

---

## 📁 Estructura MVC

Siguiendo el patrón MVC de Laravel:

```
app/
├── Models/                    # Capa de datos (Eloquent ORM)
│   ├── User.php
│   ├── Task.php
│   ├── TaskReminder.php
│   └── UserSetting.php
│
├── Controllers/               # Capa de lógica de negocio
│   ├── TaskController.php     # Operaciones CRUD
│   ├── AuthController.php     # Autenticación
│   └── UserSettingsController.php
│
└── Views/                     # Capa de presentación (Blade)
    ├── tasks/
    ├── auth/
    └── configuration/
```

### Principio KISS en Acción

**Sistema Simple de Recordatorios:**
- Se crea tarea con `due_date` → Crear recordatorio
- Scheduler verifica cada 5 minutos → Envía email si está vencida
- Usuario completa tarea → Eliminar recordatorio

**Sin sobre-ingeniería:** Sin sistemas de colas complejos, sin microservicios, sin abstracciones innecesarias. Solo código Laravel limpio y mantenible.

---

## ⚙️ Configuración

### Configuración de Email

Editar archivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario
MAIL_PASSWORD=tu_contraseña
MAIL_FROM_ADDRESS="noreply@todoapp.com"
```

### Programador de Tareas

Para notificaciones automáticas por email, añadir al crontab:

```bash
* * * * * cd /ruta-al-proyecto && php artisan schedule:run >> /dev/null 2>&1
```

**Desarrollo:** Ejecutar `php artisan schedule:work`

---

## 🗄️ Diseño de Base de Datos

**Estructura relacional simple:**

```
User (1) ──── (N) Task
  │              │
  │              └── (N) TaskReminder
  │
  └── (1) UserSetting
```

**4 Tablas Principales:**
- `users` - Autenticación
- `tasks` - Elementos de tareas (con soft deletes)
- `task_reminders` - Seguimiento de notificaciones por email
- `user_settings` - Preferencias de usuario

---

## 📚 Funcionalidad Principal

### Gestión de Tareas (Ejemplo MVC)

**Modelo** (Task.php)
```php
class Task extends Model
{
    use SoftDeletes;
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function taskReminder() {
        return $this->hasMany(TaskReminder::class);
    }
}
```

**Controlador** (TaskController.php)
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:150',
        'due_date' => 'nullable|date',
    ]);
    
    $task = Task::create([
        'user_id' => Auth::id(),
        'title' => $validated['title'],
        'due_date' => $validated['due_date'],
    ]);
    
    // KISS: Creación simple de recordatorio
    if ($task->due_date) {
        TaskReminder::create([
            'task_id' => $task->id,
            'remind_at' => $task->due_date,
        ]);
    }
    
    return redirect()->back();
}
```

**Vista** (tasks/index.blade.php)
```blade
@foreach($tasks as $task)
    <div>
        <h3>{{ $task->title }}</h3>
        <p>{{ __('tasks.due_date') }}: {{ $task->due_date }}</p>
    </div>
@endforeach
```

### Notificaciones por Email

**Comando Programado** (se ejecuta cada 5 minutos)
```bash
php artisan tasks:process-notifications
```

**Qué hace:**
1. Buscar tareas donde `due_date` <= ahora y no completadas
2. Verificar si usuario tiene `email_notifications` activadas
3. Enviar email localizado (EN/ES según configuración del usuario)
4. Marcar recordatorio como enviado

**Enfoque KISS:** Un comando hace todo. Sin colas complejas, sin sistema de eventos, solo lógica directa.

---

## 🌍 Soporte Multi-idioma

**Middleware detecta automáticamente el idioma del usuario:**

```php
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->user()?->settings?->language ?? 'en';
        app()->setLocale($locale);
        return $next($request);
    }
}
```

**Archivos de traducción:**
```
lang/
├── en/
│   └── tasks.php, emails.php, settings.php
└── es/
    └── tasks.php, emails.php, settings.php
```

**Uso:**
```blade
{{ __('tasks.title') }}  // "My Tasks" o "Mis Tareas"
```

---

## 🧪 Testing

### Test Manual

**Probar sistema de email:**
```bash
php artisan tinker
```

```php
Mail::raw('Email de prueba', function($message) {
    $message->to('tu-email@example.com')
            ->subject('Test desde Laravel');
});
```

**Probar notificaciones:**
```bash
# Ejecutar comando manualmente
php artisan tasks:process-notifications -v
```

---

## 🔒 Características de Seguridad

- ✅ Protección CSRF en formularios
- ✅ Hash de contraseñas (bcrypt)
- ✅ Prevención de inyección SQL (Eloquent ORM)
- ✅ Prevención XSS (auto-escape de Blade)
- ✅ Protección contra asignación masiva
- ✅ Middleware de autenticación

---

## 📄 Licencia

Licencia MIT - siéntete libre de usar este proyecto para aprendizaje o propósitos de portfolio.

---

## 👤 Autor

**Alejandro Pérez Paniagua**

Desarrollador Full-Stack | Especialista en Laravel

- 🌐 Portfolio: [Próximamente](#)
- 💼 LinkedIn: [linkedin.com/in/alejandro-perez-paniagua](#)
- 🐙 GitHub: [@alejandro-perez-paniagua](https://github.com/alejandro-perez-paniagua)
- 📧 Email: [contact@alejandro-perez.dev](#)

> *Este proyecto demuestra mi capacidad para construir aplicaciones web limpias y mantenibles siguiendo las mejores prácticas de la industria (MVC, KISS, PSR-12) con PHP moderno y Laravel.*

---

## 🙏 Agradecimientos

Construido con [Laravel](https://laravel.com) y [Tailwind CSS](https://tailwindcss.com)

---

<p align="center">Desarrollado con ❤️ por Alejandro Pérez Paniagua</p>
