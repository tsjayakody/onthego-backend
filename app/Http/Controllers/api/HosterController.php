<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Hoster;
use Illuminate\Http\Request;

class HosterController extends Controller
{
    public function index(Request $request)
    {
        $hosters = Hoster::query()->when($request->input('term'), function($query, $term) {
            return $query->where('name', 'like', '%'.$term.'%');
        })->limit(10)->orderBy('name', 'asc')->get();

        return response($hosters);
    }

    public function getById(Request $request, $id)
    {
        $hoster = Hoster::findOrFail($id);
        return response($hoster);
    }
}
