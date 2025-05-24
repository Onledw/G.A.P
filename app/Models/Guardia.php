<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guardia extends Model
{
    protected $table = 'Guardias';
    protected $primaryKey = 'idGuardias';

    public $timestamps = false;

    protected $fillable = [
        'Ausencias_id', 'Sesiones_id', 'ProfesorSustituto',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function ausencia(): BelongsTo
    {
        return $this->belongsTo(Ausencia::class, 'Ausencias_id');
    }

    public function sesion(): BelongsTo
    {
        return $this->belongsTo(SesionesLectivas::class, 'Sesiones_id');
    }

    public function docenteSustituto(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'ProfesorSustituto');
    }
}
