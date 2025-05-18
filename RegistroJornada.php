<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroJornada extends Model
{
    protected $table = 'RegistroJornada';
    protected $primaryKey = 'idRegistro';

    public $timestamps = false;

    protected $fillable = [
        'Docentes_idDocente', 'InicioJornada', 'FinJornada', 'DiaSemana',
    ];

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'Docentes_idDocente');
    }
}
