<?php

namespace Database\Factories;

use App\Models\Docente;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocenteFactory extends Factory
{
    protected $model = Docente::class;

    public function definition()
    {
        return [
            'dni' => $this->faker->unique()->bothify('########A'),
            'nombre' => $this->faker->firstName(),
            'apellido1' => $this->faker->lastName(),
            'apellido2' => $this->faker->lastName(),
            'clave' => bcrypt('password'),
            'admin' => $this->faker->boolean(20),
            'fecha_ingreso' => $this->faker->date(),
            'fecha_nacimiento' => $this->faker->date('Y-m-d', '2000-01-01'),
            'sexo' => $this->faker->randomElement(['H', 'M', 'O']),
        ];
    }
}
