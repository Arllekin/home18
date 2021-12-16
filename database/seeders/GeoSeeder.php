<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use App\Models\Continent;

class GeoSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $path = database_path('geodata' . DIRECTORY_SEPARATOR . 'chain.json');
        $content = file_get_contents($path);
        $items = json_decode($content, true);
        $unique_items = (array_unique($items));


        $continent_data = [];
        foreach ($unique_items as $unique_item) {
            $continent_data[] = [
                'continent_kode' => $unique_item,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        Continent::insert($continent_data);

        $continents = Continent::all()->pluck('id', 'continent_kode');

        $path = database_path('geodata' . DIRECTORY_SEPARATOR . 'countries.json');
        $content = file_get_contents($path);
        $countries = json_decode($content, true);

        $country_data = [];
        foreach ($items as $country_code => $continent_code) {
            $country_data[] = [
                'country_kode' => $country_code,
                'country_name' => $countries[$country_code],
                'continent_id' => $continents[$continent_code],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),

            ];
        }
        Country::insert($country_data);
    }
}
