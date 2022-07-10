<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatsTemp extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "stats_temp";
}
