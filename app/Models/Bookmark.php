<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $table = "bookmarks";

    protected $primaryKey = "id";

    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'word',
    ];
}
