<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = "historys";

    protected $primaryKey = "id";

    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'word',
    ];
}
