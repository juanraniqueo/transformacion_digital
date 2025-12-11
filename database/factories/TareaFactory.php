<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TareaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(),
            'descripcion' => fake()->paragraph(),
            'completada' => fake()->boolean(),
        ];
    }
}
