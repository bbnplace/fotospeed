<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'] ;

    protected function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function getProcessesArray()
    {
        $processes = [];
        $processesCollection = self::get(['name']);
        if(!empty($processesCollection))
        {
            foreach($processesCollection as $process){
                array_push($processes, $process->name);
            }
        }

        return $processes;
    }
}
