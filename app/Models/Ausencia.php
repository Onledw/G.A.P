<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ausencia extends Model
{
    protected $table = 'ausencias';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'docente_id',
        'fecha_inicio',
        'fecha_fin',
        'justificada',
        'motivo',
        'todoeldia',
    ];

    public function docente()
{
    return $this->belongsTo(Docente::class, 'docente_id');
}

}
