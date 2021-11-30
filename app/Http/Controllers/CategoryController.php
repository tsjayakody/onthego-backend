<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class CategoryController extends Controller
{
    // show all the categories with filters and paginations
    public function show(Request $request)
    {
        $categories = Category::query()
            ->when($request->input('search'), function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(8)
            ->withQueryString();

        return Inertia::render('Category/Show', [
            'categories' => $categories,
            'filters' => $request->only(['search']),
        ]);
    }

    // show create new category page
    public function create()
    {
        return Inertia::render('Category/CreateOrUpdate');
    }

    // save a new category
    public function store(Request $request)
    {
        $validatedData = $request->validateWithBag('createOrUpdateCategory', [
            'name' => ['required', 'min:2', 'max:50'],
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $category = Category::create([
            'name' => $validatedData['name']
        ]);
        $category->addMedia($request->file('photo'))->toMediaCollection('cover-image-collection');

        return redirect(route('categories.show'));
    }

    // show edit category form with requested category data
    public function edit(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        return Inertia::render('Category/CreateOrUpdate', ['category' => $category]);
    }

    // delete category by category id
    public function delete(Request $request, $id)
    {
        Category::findOrFail($id)->delete();
        return redirect(route('categories.show'));
    }

    // update category by id
    public function update(Request $request, $id)
    {
        $validatedData = $request->validateWithBag('createOrUpdateCategory', [
            'name' => ['required', 'min:2', 'max:50'],
            'photo' => ['mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $category = Category::findOrFail($id);

        $category->name = $validatedData['name'];

        if (isset($validatedData['photo'])) {
            $category->addMedia($request->file('photo'))->toMediaCollection('cover-image-collection');
        }

        $category->save();

        return redirect(route('categories.show'));
    }
}
