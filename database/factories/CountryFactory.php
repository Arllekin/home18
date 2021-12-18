<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;
use App\Models\Continent;

class CountryFactory extends Factory
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
        $country_kod = array_rand(array_unique($items));

        $continent_kod = $items[$country_kod];

        $path = database_path('geodata' . DIRECTORY_SEPARATOR . 'countries.json');
        $content = file_get_contents($path);
        $items = json_decode($content, true);
        $country_name = $items[$country_kod];

        $continent = Continent::where('continent_kode', $continent_kod)->first();

        return [
            'country_kode' => $country_kod,
            'country_name' => $country_name,
            'continent_id' => $continent->id ?? Continent::factory()->create(['continent_kode' => $continent_kod])->id,
        ];
    }
}
