<?php

namespace App\Http\Controllers\api\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SliderItem;

class GeneralController extends Controller
{
    public function getAllSliderItems(Request $request)
    {
        $sliderItems = SliderItem::query()->orderBy('order_index', 'asc')->get();

        return response($sliderItems);
    }
}
