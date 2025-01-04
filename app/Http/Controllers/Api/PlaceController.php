<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PlaceApiResource;
use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index(Request $request) {
        $places = Place::with('category', 'city');

        if ($request->has('category_id')) {
            $places->where('category_id', $request->input('category_id'));
        }

        if ($request->has('city_id')) {
            $places->where('city_id', $request->input('city_id'));
        }

        if ($request->has('limit')) {
            $places->limit($request->input('limit'));
        }
        
        return PlaceApiResource::collection($places->get());
    }

    public function show(Place $place) {
        $place->load('category', 'city','fields','photos');

        return new PlaceApiResource($place);
    }
}
