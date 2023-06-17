<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLearnExercise extends Model
{
    use HasFactory;

    protected $table = "user_learn_exercise";

    protected $primaryKey = "id";

    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'id_exercise',
    ];
}
