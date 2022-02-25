<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Episode;

class EpisodeController extends Controller
{
    // return all episodes with pagination
    public function index(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $page = $request->input('page', 1);
        $episodes = Episode::query()->when($request->input('term'), function ($query, $term) {
            return $query->where('name', 'like', '%' . $term . '%');
        })->with('show')->paginate($itemsPerPage, ['*'], 'page', $page);

        return response($episodes);
    }

    // return a single episode by slug
    public function getBySlug(Request $request, $slug)
    {
        $episode = Episode::query()->where('slug', $slug)->with('show')->firstOrFail();

        return response($episode);
    }

    // return popular episodes with pagination
    public function getPopularEpisodes(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $page = $request->input('page', 1);
        $episodes = Episode::query()->where('is_popular', 1)->with('show')->paginate($itemsPerPage, ['*'], 'page', $page);

        return response($episodes);
    }
}
