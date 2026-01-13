<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "title",
        "content",
        "user_id",
        "due_date",
        "completed_at"
    ];
    protected $casts = [
        'due_date'     => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taskReminder()
    {
        return $this->hasMany(TaskReminder::class);
    }
}
