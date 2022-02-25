<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hoster;

class HosterController extends Controller
{
    // return all hosters with pagination
    public function index(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $page = $request->input('page', 1);
        $hosters = Hoster::query()->when($request->input('term'), function($query, $term) {
            return $query->where('name', 'like', '%'.$term.'%');
        })->where('is_approved', true)->paginate($itemsPerPage, ['*'], 'page', $page);

        return response($hosters);
    }

    // return a single hoster by slug
    public function getBySlug(Request $request, $slug)
    {
        $hoster = Hoster::query()->where('slug', $slug)->firstOrFail();

        return response($hoster);
    }
}
