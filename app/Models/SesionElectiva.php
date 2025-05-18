<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionElectiva extends Model
{
    protected $table = 'sesiones_lectivas';

    protected $fillable = [
        'docente_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'aula',
        'materia',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }
}
