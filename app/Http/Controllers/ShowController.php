<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Show;
use App\Models\Hoster;
use Inertia\Inertia;

class ShowController extends Controller
{
    // show all shows with pagination and filters
    public function show(Request $request)
    {
        $shows = Show::query()
            ->join('hosters', 'shows.hoster_id', '=', 'hosters.id')
            ->when($request->input('search'), function ($query, $search) {
                return $query
                    ->where('shows.name', 'like', '%' . $search . '%')->orWhere('hosters.name', 'like', '%' . $search . '%');
            })
            ->orderBy('shows.name', 'asc')
            ->select('shows.name', 'shows.slug', 'hosters.name as hoster_name', 'shows.id')
            ->paginate(8)
            ->withQueryString();

        return Inertia::render('Show/Show', [
            'shows' => $shows,
            'filters' => $request->only(['search']),
        ]);
    }

    // show create new show page
    public function create(Request $request)
    {
        $hosters = Hoster::query()->select(['id', 'name'])
            ->when($request->input('term'), function ($query, $term) {
                return $query->where('name', 'like', '%' . $term . '%');
            })
            ->limit(10)
            ->get();
        return Inertia::render('Show/CreateUpdate', ['hosters' => $hosters]);
    }

    // save a new show
    public function store(Request $request)
    {
        $validatedData = $request->validateWithBag('createUpdateShow', [
            'name' => ['required', 'min:2', 'max:255'],
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:1024'],
            'description' => ['required', 'min:50', 'string'],
            'hoster_id' => ['required', 'exists:hosters,id']
        ]);

        $show = Show::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'hoster_id' => $validatedData['hoster_id']
        ]);
        $show->addMedia($request->file('photo'))->toMediaCollection('show-image-collection');

        return redirect(route('shows.show'));
    }

    // update show by id
    public function update(Request $request, $id)
    {
        $show = Show::findOrFail($id);

        $validatedData = $request->validateWithBag('createUpdateShow', [
            'name' => ['required', 'min:2', 'max:255'],
            'photo' => ['mimes:jpg,jpeg,png', 'max:1024'],
            'description' => ['required', 'min:50'],
            'hoster_id' => ['required', 'exists:hosters,id']
        ]);

        $show->name = $validatedData['name'];
        $show->description = $validatedData['description'];
        $show->hoster_id = $validatedData['hoster_id'];

        if (isset($validatedData['photo'])) {
            $show->addMedia($request->file('photo'))->toMediaCollection('show-image-collection');
        }

        $show->save();

        return redirect(route('shows.show'));
    }

    // show edit show form with requested show data
    public function edit(Request $request, $id)
    {
        $show = Show::findOrFail($id);
        return Inertia::render('Show/CreateUpdate', ['show' => $show]);
    }

    // delete hoster by hoster id
    public function delete(Request $request, $id)
    {
        Show::findOrFail($id)->delete();
        return redirect(route('shows.show'));
    }
}
