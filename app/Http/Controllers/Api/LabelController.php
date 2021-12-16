<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LabelCollection;
use App\Models\Label;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LabelController
{
    public function index()
    {
    }
    public function create()
    {

    }
    public function store(Request $request)
    {
        $label_names = $request->validate([
            '*.label_body' => ['required'],
        ]);

        $items = [];
        $counter = count($label_names);
        for ($q = 0; $q<$counter; $q++) {
            $items[$q] = $label_names[$q];
            $items[$q]['author_id'] = Auth::id();
            $items[$q]['created_at'] = date('Y-m-d H:i:s');
            $items[$q]['updated_at'] = date('Y-m-d H:i:s');
        }

        return Label::insert($items);
    }

    public function link(Request $request)
    {

        $data = $request->validate([
            '*.project_id' => ['required'],
            '*.label_id' => ['required'],
        ]);

        $data = collect($data);
        $labels = Label::query()->whereIn('id', $data->pluck('label_id'))->get()->toArray();
        $projects = Project::query()->whereIn('id', $data->pluck('project_id'))->get()->toArray();

        $counter = count($labels);
        for ($q = 0; $q<$counter; $q++) {
            if(Auth::id() !== $labels[$q]['author_id'] && Auth::id() !== $projects[$q]['creator_id']) {
                return response(null, '403 Unauthorized');
            };
        }

        $attaches = [];
        foreach ($data as $item) {
            $attaches[] = [
                'project_id' => $item['project_id'],
                'label_id' => $item['label_id'],
            ];
        }
        DB::table('label_project')->insert($attaches);

        return response(null);
    }

    public function list(Request $request)
    {

        $query = Label::query();


        if($request->has('emails')){
            $emails = $request->get('emails');

            $query->whereHas('author', function(Builder $qb) use ($emails) {
                $qb->whereIn('email', $emails);
            });
        }

        if($request->has('projects')){
            $projects = $request->get('projects');

            $query->whereHas('project', function(Builder $qb) use ($projects) {
                $qb->whereIn('projects.id', $projects);
            });
        }

        return new LabelCollection($query->get());

    }
    public function edit()
    {

    }
    public function update()
    {

    }

    public function destroy(Request $request)

    {
        $id = $request->get('id');
        $labels = Label::query()->whereIn('id', $id)->get();

        foreach ($labels as $label) {
            if (Auth::id() !== $label->author_id) {
                return response(null, '403 Unauthorized');
            }
        }

        Label::query()->whereIn('id', $id)->delete();
    }

}

