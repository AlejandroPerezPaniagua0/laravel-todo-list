<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'theme',
        'language',
        'email_notifications',
        'timezone',
        'date_format'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
    ];

    protected $attributes = [
        'theme' => 'light',
        'language' => 'es',
        'email_notifications' => true,
        'timezone' => 'UTC',
        'date_format' => 'd/m/Y',
    ];

    // Relación con el usuario
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    // Opciones disponibles para cada campo
    public static function getThemeOptions()
    {
        return [
            'light' => 'Claro',
            'dark' => 'Oscuro',
        ];
    }

    public static function getLanguageOptions()
    {
        return [
            'es' => 'Español',
            'en' => 'English',
        ];
    }

    public static function getDateFormatOptions()
    {
        return [
            'd/m/Y' => 'DD/MM/YYYY (31/12/2026)',
            'Y-m-d' => 'YYYY-MM-DD (2026-12-31)',
            'm/d/Y' => 'MM/DD/YYYY (12/31/2026)',
        ];
    }

    public static function getTimezoneOptions()
    {
        return [
            'UTC' => 'UTC (Universal)',
            'Europe/Madrid' => 'Madrid (Spain)',
            'America/New_York' => 'Nueva York (USA)',
            'America/Los_Angeles' => 'Los Ángeles (USA)',
            'America/Mexico_City' => 'Mexico City (Mexico)',
            'America/Buenos_Aires' => 'Buenos Aires (Argentina)',
        ];
    }
}
