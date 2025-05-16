<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CorreoEnviado extends Model
{
    use HasFactory;

    protected $table = 'correos_enviados';

    protected $fillable = ['destinatario', 'asunto', 'mensaje', 'enviado_en'];


    protected $casts = [
        'enviado_en' => 'datetime',
    ];
}
