<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Label;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GeoSeeder::class);

        $countries = \App\Models\Country::all();
        $users = \App\Models\User::factory()->count(10)->make(['country_id' => null]);
        $users->each(function (User $user) use ($countries) {
            $user->country()->associate($countries->random());
            $user->save();
            $user->createToken('web');
        });

        $users = \App\Models\User::all()->pluck('id');
        $labels = \App\Models\Label::factory()->count(20)->make(['author_id' => null]);
        $labels->each(function (Label $label) use ($users) {
            $label->author()->associate($users->random());
            $label->save();
        });

        $users = \App\Models\User::all();
        $projects = \App\Models\Project::factory()->count(10)->make(['creator_id' => null]);
        $projects->each(function (Project $project) use ($users) {
            $project->creator()->associate($users->random());
            $project->save();
        });


//        $users = \App\Models\User::all()->pluck('id');
//        $user_id = [];
//        $projects = \App\Models\Project::all();
//        foreach ($projects as $project) {
//            $creator = $project->creator_id;
//            for($a = $users->random(), $counter = count($user_id); $counter<2; $a = $users->random(), $counter = count($user_id)) {
//                if(isset($a) && $a !== $creator) {
//                    $user_id[] = $a;
//                }
//            }
//            $project->user()->sync($user_id);
//        }



        $users = \App\Models\User::all();
        $projects = \App\Models\Project::all();
        $creators = $projects->pluck('creator_id', 'id');

        foreach ($projects as $project) {
            $user_id = $users->pluck('id')->random(2)->toArray();
            $creator = $creators[$project->id];
            array_push($user_id, $creator);
            $project->user()->sync($user_id);
        }


        $labels = \App\Models\Label::all();
        $projects = \App\Models\Project::all();
        foreach ($projects as $project) {
            $label_id = $labels->pluck('id')->random(2);
            $project->label()->sync($label_id);
        }


    }
}
