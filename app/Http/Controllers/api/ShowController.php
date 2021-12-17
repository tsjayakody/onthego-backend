<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Show;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function index(Request $request)
    {
        $hosters = Show::query()
        ->with('hoster')
        ->whereHas('hoster', function ($query) {
            $query->where('is_approved', true);
        })
        ->when($request->input('term'), function ($query, $term) {
            return $query->where('name', 'like', '%' . $term . '%');
        })->limit(10)->orderBy('name', 'asc')->get();

        return response($hosters);
    }

    public function getById(Request $request, $id)
    {
        $hoster = Show::with('hoster')->where('id', $id)->get();
        return response($hoster);
    }
}
