<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Docente extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'docentes';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'dni', 'nombre', 'apellido1', 'apellido2', 'clave', 'admin', 'fecha_ingreso', 'fecha_nacimiento', 'sexo',
    ];

    protected $hidden = [
        'clave',
    ];

    public function getAuthIdentifierName()
    {
        return 'dni';
    }

    public function getAuthPassword()
    {
        return $this->clave;
    }

    public function ausencias(): HasMany
    {
        return $this->hasMany(Ausencia::class, 'docente_id', 'id');
    }

    public function sesiones()
    {
        return $this->hasMany(SesionElectiva::class, 'docente_id', 'id');
    }

    public function jornadas()
    {
        return $this->hasMany(RegistroJornada::class, 'docente_id', 'id');
    }
}
