<?php

// app/Http/Controllers/LocationController.php
namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    // Get all countries
    public function countries()
    {
        $countries = Country::all();

        // Log the retrieval of countries
        Log::info('Retrieved all countries');

        return response()->json($countries);
    }

    // Get states for a specific country
    public function states($country_id)
    {
        $states = State::where('country_id', $country_id)->get();

        // Log the retrieval of states for a specific country
        Log::info('Retrieved states for country_id: ' . $country_id);

        return response()->json($states);
    }

    // Get cities for a specific state
    public function cities($state_id)
    {
        $cities = City::where('state_id', $state_id)->get();

        // Log the retrieval of cities for a specific state
        Log::info('Retrieved cities for state_id: ' . $state_id);

        return response()->json($cities);
    }
}
