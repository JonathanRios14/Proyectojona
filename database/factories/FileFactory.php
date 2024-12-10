<?php
namespace Database\Factories;

use App\Models\File;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'ruta' => $this->faker->filePath(),
            'project_id' => Project::inRandomOrder()->first()->id,
        ];
    }
}
