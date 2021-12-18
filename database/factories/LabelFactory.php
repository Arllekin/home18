<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LabelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'label_body' => $this->faker->sentence(rand(1, 2)),
            'author_id' => \App\Models\User::factory(),
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        ];
    }
}
