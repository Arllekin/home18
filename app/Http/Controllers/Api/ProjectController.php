<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProjectCollection;
use App\Models\Label;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ProjectController
{
    public function store(Request $request)
    {
        $project_names = $request->validate([
            '*.project_name' => ['required'],
        ]);

        $items = [];
        $counter = count($project_names);
        for ($q = 0; $q<$counter; $q++) {
            $items[$q] = $project_names[$q];
            $items[$q]['creator_id'] = Auth::id();
            $items[$q]['created_at'] = date('Y-m-d H:i:s');
            $items[$q]['updated_at'] = date('Y-m-d H:i:s');
        }

        Project::insert($items);

        $projects = Project::query()
            ->whereIn('project_name', $project_names)
            ->where('creator_id', '=', Auth::id())
            ->get();

        $attaches = [];
        foreach ($projects as $project) {
            $attaches[] = [
                'project_id' => $project->id,
                'user_id' => Auth::id()
            ];
        }

        DB::table('project_user')->insert($attaches);
    }

    public function link( Request $request)
    {
        $data = $request->validate([
            '*.user_id' => ['required', 'exists:users,id'],
            '*.project_id' => ['required', 'exists:projects,id'],
        ]);

        $data = collect($data);

        $projects = Project::query()->whereIn('id', $data->pluck('project_id'))->get();

        foreach ($projects as $project) {
            if(Auth::id() !== $project->creator_id) {
                return response(null, '403 Unauthorized');
            };
        }

        $attaches = [];
        foreach ($data as $item) {
            $attaches[] = [
                'project_id' => $item['project_id'],
                'user_id' => $item['user_id'],
            ];
        }

        DB::table('project_user')->insert($attaches);

        return response(null);
    }

    public function list(Request $request)
    {
        $query = Project::query();

        $query->whereHas('user', function (Builder $qb) {
            $qb->where('user_id', '=', Auth::id());
        });

        if($request->has('emails')){
            $emails = $request->get('emails');

            $query->whereHas('creator', function(Builder $qb) use ($emails) {
                $qb->whereIn('email', $emails);
            });
        }

        if($request->has('continents')){
            $continents = $request->get('continents');

            $query->whereHas('creator', function(Builder $qb) use ($continents)  {
                $qb->whereHas('country', function (Builder $qb) use ($continents) {
                        $qb->whereIn('continent_id', $continents);
                    });
                });
        }

        if($request->has('labels')){
            $labels = $request->get('labels');

            $query->whereHas('label', function(Builder $qb) use ($labels) {
                $qb->whereIn('labels.id', $labels);
            });
        }
        return new ProjectCollection($query->get());
    }

    public function destroy(Request $request)
    {
        $id = $request->get('id');
        $projects = Project::query()->whereIn('id', $id)->get();

        foreach ($projects as $project) {
            if (Auth::id() !== $project->creator_id) {
                return response(null, '403 Unauthorized');
            }
        }

        Project::query()->whereIn('id', $id)->delete();
    }

}
