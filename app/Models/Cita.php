<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'tipo',
        "date",
        "hour",
        "user_dni",
        "user_email",
        "user_name",
        "user_telefono"
    ];

}
