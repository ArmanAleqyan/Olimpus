<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fild extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function sport_type() {
        return $this->hasMany(FildSportType::class, 'fild_id');
    }
    public function grafik() {
        return $this->hasMany(FildGrafik::class, 'fild_id');
    }
    public function photo() {
        return $this->hasMany(FildPhoto::class, 'fild_id');
    }
}
