<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guardia extends Model
{
    protected $table = 'guardias';
    protected $primaryKey = 'id';

    // Permite que Laravel administre created_at y updated_at
    public $timestamps = true;

    protected $fillable = [
        'ausencia_id',
        'docente_id',
        'fecha',
        'hora',
        'aula',
        'estado',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function ausencia(): BelongsTo
    {
        return $this->belongsTo(Ausencia::class, 'ausencia_id');
    }

    public function docenteSustituto(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'docente_id');
    }
}
