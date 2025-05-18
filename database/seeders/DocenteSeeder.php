<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Docente;


class DocenteSeeder extends Seeder
{
    public function run(): void
    {
        Docente::updateOrCreate(
            ['dni' => '12345678A'],
            [
                'nombre' => 'Ana',
                'apellido1' => 'García',
                'apellido2' => 'López',
                'clave' => Hash::make('1234'),
                'admin' => 0,
                'fecha_ingreso' => '2022-09-01',
                'fecha_nacimiento' => '1990-05-15',
                'sexo' => 'M',
            ]
        );

        Docente::updateOrCreate(
            ['dni' => '87654321B'],
            [
                'nombre' => 'Carlos',
                'apellido1' => 'Martínez',
                'apellido2' => 'Ruiz',
                'clave' => Hash::make('adminpass'),
                'admin' => 1,
                'fecha_ingreso' => '2020-01-10',
                'fecha_nacimiento' => '1985-08-20',
                'sexo' => 'H',
            ]
        );
    }
}
