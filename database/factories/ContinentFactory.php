<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContinentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $path = database_path('geodata' . DIRECTORY_SEPARATOR . 'chain.json');
        $content = file_get_contents($path);
        $items = json_decode($content, true);
        $unique_item = array_rand(array_unique($items));

        return [
            'continent_kode' => $unique_item,
        ];
    }
}
