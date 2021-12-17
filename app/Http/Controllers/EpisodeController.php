<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Episode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EpisodeController extends Controller
{
    // list all episodes with filters and pagination
    public function show(Request $request) {
        $episodes = Episode::query()
        ->with('show')
        ->when($request->input('search'), function($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
        ->when($request->input('featured'), function($query, $featured) {
            return $query->where('is_featured', $featured);
        })
        ->when($request->input('popular'), function($query, $popular) {
            return $query->where('is_popular', $popular);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Episode/Show', [
            'episodes' => $episodes,
            'filters' => $request->only(['search', 'featured', 'popular']),
        ]);
    }

    // returns the episode create page
    public function create() {
        return Inertia::render('Episode/CreateUpdate', ['categories' => Category::all()]);
    }

    // create new episode
    public function store(Request $request) {
        $validatedData = $request->validateWithBag('createUpdateEpisode', [
            'name' => ['required', 'min:2', 'max:50'],
            'description' => ['required', 'min:50', 'string'],
            'media_url' => ['required', 'url'],
            'spotify' => ['url'],
            'google_podcast' => ['url'],
            'apple_podcast' => ['url'],
            'tags' => ['array'],
            'featured_hosts' => ['array'],
            'categories' => ['array'],
            'is_featured' => ['required', 'boolean'],
            'is_popular' => ['required', 'boolean'],
            'show_id' => ['required', 'exists:shows,id'],
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);
        $episode = Episode::create($validatedData);

        if (sizeof($validatedData['categories']) > 0) {
            $episode->categories()->attach($validatedData['categories']);
        }

        $episode->addMedia($request->file('photo'))->toMediaCollection('episode-image-collection');
        return redirect(route('episodes.show'));
    }

    // returns the episode edit page
    public function edit(Request $request, $id) {
        $episode = Episode::with(['show', 'categories'])->where('id', $id)->get();
        return Inertia::render('Episode/CreateUpdate', ['episode' => $episode[0], 'categories' => Category::all()]);
    }

    // update the episode
    public function update(Request $request, $id) {
        $validatedData = $request->validateWithBag('createUpdateEpisode', [
            'name' => ['required', 'min:2', 'max:50'],
            'description' => ['required', 'min:50', 'string'],
            'media_url' => ['required', 'url'],
            'spotify' => ['url'],
            'google_podcast' => ['url'],
            'apple_podcast' => ['url'],
            'tags' => ['array'],
            'featured_hosts' => ['array'],
            'categories' => ['array'],
            'is_featured' => ['required', 'boolean'],
            'is_popular' => ['required', 'boolean'],
            'show_id' => ['required', 'exists:shows,id'],
        ]);
        $episode = Episode::findOrFail($id);
        $episode->fill($validatedData)->save();
        if (sizeof($validatedData['categories']) > 0) {
            $episode->categories()->sync($validatedData['categories']);
        }
        if (isset($validatedData['photo'])) {
            $episode->addMedia($request->file('photo'))->toMediaCollection('episode-image-collection');
        }

        return redirect(route('episodes.show'));
    }

    // delete the episode
    public function delete(Request $request, $id) {
        Episode::destroy($id);
        return redirect(route('episodes.show'));
    }
}
