<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // return all categories with pagination
    public function index(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $page = $request->input('page', 1);
        $categories = Category::query()->when($request->input('term'), function ($query, $term) {
            return $query->where('name', 'like', '%' . $term . '%');
        })->orderBy('name', 'asc')->paginate($itemsPerPage, ['*'], 'page', $page);

        return response($categories);
    }

    // return a single category by slug
    public function getBySlug(Request $request, $slug)
    {
        $category = Category::query()->where('slug', $slug)->firstOrFail();

        return response($category);
    }
}
