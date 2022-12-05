<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    use HasFactory;
    
    public static function log($par = []){
        DB::table("__logs")->insert([
            'log' => json_encode($par),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return 1;
    }
}
