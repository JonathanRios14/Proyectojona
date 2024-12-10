<?php
namespace Database\Factories;

use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition()
    {
        $estudiante = User::where('role', 'estudiante')->inRandomOrder()->first();

        $profesor = User::where('role', 'profesor')->inRandomOrder()->first();

        return [
            'nombre' => $this->faker->sentence(3),
            'descripcion' => $this->faker->paragraph(),
            'estudiante_id' => $estudiante ? $estudiante->id : User::first()->id,
            'profesor_id' => $profesor ? $profesor->id : User::first()->id,
            'estado' => $this->faker->randomElement(['activo', 'completado']),
        ];
    }
}
