<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;
    
    const STATUS_TODO = 1;
    const STATUS_DOING = 2;
    const STATUS_DONE = 3;
    const STATUS_CANCELLED = 4;
    const STATUS_HELD = 5;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


    /**
     * Get Task Status ID
     * @param string $task_status
     * @return int
     */
    public static function getTaskStatusId(string $task_status): int
    {
        switch (strtolower($task_status)) {
            case 'todo':
                return self::STATUS_TODO;
            case 'doing':
                return self::STATUS_DOING;
            case 'done':
                return self::STATUS_DONE;
            case 'cancelled':
                return self::STATUS_CANCELLED;
            case 'held':
                return self::STATUS_HELD;
            default:
                return 0;
        }
    }
}
