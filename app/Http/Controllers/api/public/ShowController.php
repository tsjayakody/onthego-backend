<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Show;
use App\Models\Episode;

class ShowController extends Controller
{
    // return all shows with pagination
    public function index(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $page = $request->input('page', 1);
        $shows = Show::query()->when($request->input('term'), function($query, $term) {
            return $query->where('name', 'like', '%'.$term.'%');
        })->paginate($itemsPerPage, ['*'], 'page', $page);

        return response($shows);
    }

    // return a single show by slug
    public function getBySlug(Request $request, $slug)
    {
        $show = Show::query()->where('slug', $slug)->firstOrFail();

        return response($show);
    }

    // return episodes by show slug
    public function getEpisodesByShowSlug(Request $request, $slug)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $page = $request->input('page', 1);
        $episodes = Show::query()->where('slug', $slug)->firstOrFail()->episodes()->paginate($itemsPerPage, ['*'], 'page', $page);

        return response($episodes);
    }
}
