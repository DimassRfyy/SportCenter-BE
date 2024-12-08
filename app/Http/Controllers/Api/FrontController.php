<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Place;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index() {
        $places = Place::with('category')->get()->map(function ($place) {
            $place->thumbnail_full_url = $place->thumbnail_url;
            return $place;
        });

        $categories = Category::all()->map(function ($category) {
            $category->thumbnail_full_url = $category->thumbnail_url;
            return $category;
        });

        $cities = City::all()->map(function ($city) {
            $city->icon_full_url = $city->icon_url;
            return $city;
        });

        return response()->json([
            'places' => $places,
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }
}
