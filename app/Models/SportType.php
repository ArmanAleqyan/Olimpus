<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function filds() {
        return $this->Hasmany(FildSportType::class,'sport_id');
    }
}
