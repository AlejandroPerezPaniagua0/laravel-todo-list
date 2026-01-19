<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskReminder extends Model
{
    use HasFactory;
    protected $fillable = [
        "remind_at",
        "send_at",
        "task_id"
    ];

    protected $casts = [
        "remind_at" => "datetime",
        "send_at" => "datetime"
    ];
    public function task() 
    {
        return $this->belongsTo(Task::class);
    }
}
