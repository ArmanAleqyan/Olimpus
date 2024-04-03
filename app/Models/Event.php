<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function fild() {
        return $this->belongsto(Fild::class, 'fild_id');
    }


    public function grafik() {
        return $this->belongsto(FildGrafik::class, 'grafik_id');
    }
}
