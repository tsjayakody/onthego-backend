<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    // list all episodes with filters and pagination
    public function index(Request $request) {}

    // returns the episode create page
    public function create() {}

    // create new episode
    public function store(Request $request) {}

    // returns the episode edit page
    public function edit(Request $request, $id) {}

    // update the episode
    public function update(Request $request, $id) {}

    // delete the episode
    public function delete(Request $request, $id) {}
}
