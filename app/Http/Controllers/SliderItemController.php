<?php

namespace App\Http\Controllers;

use App\Models\SliderItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SliderItemController extends Controller
{
    // return the slider item list and show listing page
    public function show()
    {
        $sliderImages = SliderItem::orderBy('order_index', 'asc')->get();
        return Inertia::render('SliderImage/Show', ['images' => $sliderImages]);
    }

    // show slider item create page
    public function create()
    {
        return Inertia::render('SliderImage/CreateUpdate');
    }

    // create new slider item
    public function store(Request $request)
    {
        $sliderItem = new SliderItem();
        $validatedData = $request->validateWithBag('createUpdateSliderItem', [
            'title' => ['required', 'min:2', 'max:100'],
            'description' => ['required', 'min:50', 'string'],
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $sliderItem->fill($validatedData);

        // get max order index number.
        // this gives a integer number.
        // if no records in the table it gives null.
        $maxOrderIndex = SliderItem::max('order_index');

        // if max order index null; assign 1 to the slider item order_index
        if ($maxOrderIndex == null) {
            $sliderItem->order_index = 1;
        } else {
            // else assign the max order index + 1 to the slider item order_index
            $sliderItem->order_index = $maxOrderIndex + 1;
        }

        $sliderItem->save();

        $sliderItem->addMedia($request->file('photo'))->toMediaCollection('slider-image-collection');
        return redirect(route('sliderImages.show'));
    }

    // show slider item edit page
    public function edit(Request $request, $id)
    {
        $sliderItem = SliderItem::findOrFail($id);
        return Inertia::render('SliderImage/CreateUpdate', ['sliderItem' => $sliderItem]);
    }

    // update slider item
    public function update(Request $request, $id)
    {
        $sliderItem = SliderItem::findOrFail($id);
        $validatedData = $request->validateWithBag('createUpdateSliderItem', [
            'title' => ['required', 'min:2', 'max:100'],
            'description' => ['required', 'min:50', 'string'],
            'photo' => ['mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $sliderItem->fill($validatedData);
        $sliderItem->save();

        if ($request->hasFile('photo')) {
            $sliderItem->addMedia($request->file('photo'))->toMediaCollection('slider-image-collection');
        }

        return redirect(route('sliderImages.show'));
    }

    // delete the slider item
    public function delete(Request $request, $id)
    {
        $sliderItem = SliderItem::findOrFail($id);
        $sliderItem->delete();
        return redirect(route('sliderImages.show'));
    }

    // change the slider item display order
    public function changeOrder(Request $request)
    {
        DB::transaction(function () use ($request) {
            $sliderItem = SliderItem::findOrFail($request->input('id')); // get the slider item from id
            if ($request->input('direction') == 'up') { // if item moves up
                if (SliderItem::min('order_index') < $sliderItem->order_index) { // if item not in the highest position
                    SliderItem::where('order_index', $sliderItem->order_index - 1)->update(['order_index' => DB::raw('order_index + 1')]);
                    $sliderItem->order_index = $sliderItem->order_index - 1;
                }
            } else {
                if (SliderItem::max('order_index') > $sliderItem->order_index) { // if item not in the lowest position
                    SliderItem::where('order_index', $sliderItem->order_index + 1)->update(['order_index' => DB::raw('order_index - 1')]);
                    $sliderItem->order_index = $sliderItem->order_index + 1;
                }
            }
            $sliderItem->save();
        });
        return redirect(route('sliderImages.show'));
    }
}
