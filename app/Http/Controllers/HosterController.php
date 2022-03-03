<?php

namespace App\Http\Controllers;

use App\Models\Hoster;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class HosterController extends Controller
{
    // show all the hosters with filters and paginations
    public function show(Request $request)
    {
        $hosters = Hoster::query()
            ->when($request->input('search'), function ($query, $search) {
                if ($search == "approved") {
                    return $query->where('is_approved', '=', true);
                } elseif ($search == "notapproved") {
                    return $query->where('is_approved', '=', false);
                }
                return $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('contact_number', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(8)
            ->withQueryString();

        return Inertia::render('Hoster/Show', [
            'hosters' => $hosters,
            'filters' => $request->only(['search']),
        ]);
    }

    // show create new hoster page
    public function create()
    {
        return Inertia::render('Hoster/CreateUpdate');
    }

    // save a new hoster
    public function store(Request $request)
    {
        $validatedData = $request->validateWithBag('createUpdateHoster', [
            'name' => ['required', 'min:2', 'max:255'],
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:1024'],
            'email' => ['email', 'required', 'unique:hosters,email'],
            'contact_number' => ['required', 'digits:10', 'unique:hosters,contact_number'],
            'description' => ['required', 'min:50', 'string'],
            'linkedin' => ['nullable', 'url', 'unique:hosters,linkedin'],
            'facebook' => ['nullable', 'url', 'unique:hosters,facebook'],
            'twitter' => ['nullable', 'url', 'unique:hosters,twitter'],
            'instagram' => ['nullable', 'url', 'unique:hosters,instagram'],
            'anchor' => ['nullable', 'url', 'unique:hosters,anchor'],
            'past_works' => ['string'],
            'youtube' => ['nullable', 'url', 'unique:hosters,youtube'],
            'designation' => ['required', 'min:3'],
            'is_approved' => ['required', 'boolean'],
        ]);

        $category = Hoster::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'email' => $validatedData['email'],
            'contact_number' => $validatedData['contact_number'],
            'linkedin' => $validatedData['linkedin'],
            'facebook' => $validatedData['facebook'],
            'twitter' => $validatedData['twitter'],
            'instagram' => $validatedData['instagram'],
            'youtube' => $validatedData['youtube'],
            'anchor' => $validatedData['anchor'],
            'past_works' => $validatedData['past_works'],
            'designation' => $validatedData['designation'],
            'is_approved' => $validatedData['is_approved'],
        ]);
        $category->addMedia($request->file('photo'))->toMediaCollection('hoster-image-collection');

        return redirect(route('hosters.show'));
    }

    // show edit hoster form with requested hoster data
    public function edit(Request $request, $id)
    {
        $hoster = Hoster::findOrFail($id)->makeVisible(['past_works', 'anchor', 'contact_number', 'email']);
        return Inertia::render('Hoster/CreateUpdate', ['hoster' => $hoster]);
    }

    // delete hoster by hoster id
    public function delete(Request $request, $id)
    {
        Hoster::findOrFail($id)->delete();
        return redirect(route('hosters.show'));
    }

    // update hoster by id
    public function update(Request $request, $id)
    {
        $hoster = Hoster::findOrFail($id);
        $validatedData = $request->validateWithBag('createUpdateHoster', [
            'name' => ['required', 'min:2', 'max:255'],
            'photo' => ['mimes:jpg,jpeg,png', 'max:1024'],
            'email' => ['email', 'required', Rule::unique('hosters')->ignore($hoster->id)],
            'contact_number' => ['required', 'digits:10', Rule::unique('hosters')->ignore($hoster->id)],
            'description' => ['required', 'min:50'],
            'linkedin' => ['nullable', 'url', Rule::unique('hosters')->ignore($hoster->id)],
            'facebook' => ['nullable', 'url', Rule::unique('hosters')->ignore($hoster->id)],
            'twitter' => ['nullable', 'url', Rule::unique('hosters')->ignore($hoster->id)],
            'instagram' => ['nullable', 'url', Rule::unique('hosters')->ignore($hoster->id)],
            'youtube' => ['nullable', 'url', Rule::unique('hosters')->ignore($hoster->id)],
            'anchor' => ['nullable', 'url', Rule::unique('hosters')->ignore($hoster->id)],
            'past_works' => ['string'],
            'designation' => ['required', 'min:3'],
            'is_approved' => ['required', 'boolean'],
        ]);

        $hoster->fill($validatedData);

        if (isset($validatedData['photo'])) {
            $hoster->addMedia($request->file('photo'))->toMediaCollection('hoster-image-collection');
        }

        $hoster->save();

        return redirect(route('hosters.show'));
    }
}
