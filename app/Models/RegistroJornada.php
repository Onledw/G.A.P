<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroJornada extends Model
{
    protected $table = 'registro_jornada';

    protected $fillable = ['docente_id', 'inicio', 'fin'];

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'docente_id', 'id');
    }

}
