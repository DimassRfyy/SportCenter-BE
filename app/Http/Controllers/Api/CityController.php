<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CityApiResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request) {
        $cities = City::withCount('places');

        if ($request->has('limit')) {
            $cities->limit($request->input('limit'));
        }

        return CityApiResource::collection($cities->get());
    }

    public function show(City $city) {
        $city->load('places');
        $city->loadCount('places');

        return new CityApiResource($city);
    }
}
