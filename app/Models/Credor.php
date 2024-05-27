<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credor extends Model
{
    use HasFactory;

    protected $table = 'credor';
    protected $fillable = [
        'nome',
    ];
}
