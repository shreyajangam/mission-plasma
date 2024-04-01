<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlasmaDonor;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class PlasmaDonorController extends Controller
{
    // Show the form for creating a new donor
    public function showDonorForm()
    {
        return view('pages.plasmaDonor.donor_form');
    }

    // Store a newly created donor in storage
    public function storeDonorForm(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string',
                'gender' => 'required|in:male,female',
                'age' => 'required|integer|min:18|max:60',
                'blood_group' => 'required|in:O+,O-,A+,A-,B+,B-,AB+,AB-',
                'covid_positive_date' => 'required|date',
                'covid_negative_date' => 'required|date',
                'country' => 'required|string',
                'state' => 'required|string',
                'city' => 'required|string',
                'phone_number' => 'required|string',
            ]);

            // Start a database transaction
            DB::beginTransaction();

            // Create a new PlasmaDonor instance
            $donor = new PlasmaDonor();
            $donor->name = $request->input('name');
            $donor->gender = $request->input('gender');
            $donor->age = $request->input('age');
            $donor->blood_group = $request->input('blood_group');
            $donor->covid_positive_date = $request->input('covid_positive_date');
            $donor->covid_negative_date = $request->input('covid_negative_date');
            $donor->country_id = $request->input('country');
            $donor->state_id = $request->input('state');
            $donor->city_id = $request->input('city');
            $donor->phone_number = $request->input('phone_number');
            $donor->save();

            // Commit the transaction
            DB::commit();

            // Log the successful submission of donor details
            Log::info('Donor details submitted successfully', ['donor_id' => $donor->id]);

            return response()->json(['message' => 'Donor details submitted successfully']);
        } catch (Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();

            // Log the error
            Log::error('Error submitting donor details', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'An error occurred while submitting donor details'], 500);
        }
    }

    // Show the list of plasma requests
    public function showRequestsList()
    {
        return view('pages.plasmaRequest.requests_list');
    }

    // Get a list of plasma donors with optional filters
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10); // Default page size is 10
        $donors = PlasmaDonor::query();

        // Apply filters if provided in the request
        if ($request->filled('country')) {
            $donors->whereHas('country', function ($query) use ($request) {
                $query->where('name', $request->input('country'));
            });
        }
        if ($request->filled('state')) {
            $donors->whereHas('state', function ($query) use ($request) {
                $query->where('name', $request->input('state'));
            });
        }
        if ($request->filled('blood_group')) {
            $donors->where('blood_group', $request->input('blood_group'));
        }

        // Add a filter for valid donors based on Covid-19 positive date
        $validDate = now()->subDays(14)->toDateString();
        $donors->where('covid_positive_date', '<=', $validDate);

        // Order by the latest timestamp (updated_at or created_at)
        $donors->latest();

        $plasmaDonorsPaginator = $donors->paginate($pageSize);

        // Transform the items in the paginator
        $transformedDonors = [];
        foreach ($plasmaDonorsPaginator->items() as $donor) {
            $transformedDonors[] = [
                'id' => $donor->id,
                'name' => $donor->name,
                'gender' => $donor->gender,
                'age' => $donor->age,
                'blood_group' => $donor->blood_group,
                'covid_positive_date' => $donor->covid_positive_date,
                'covid_negative_date' => $donor->covid_negative_date,
                'country' => $donor->country['name'],
                'state' => $donor->state['name'],
                'city' => $donor->city['name'],
                'phone_number' => $donor->phone_number,
            ];
        }

        // Create a new array that includes the transformed donors and pagination information
        $transformedData = [
            'data' => $transformedDonors,
            'total' => $plasmaDonorsPaginator->total(),
            'perPage' => $plasmaDonorsPaginator->perPage(),
            'currentPage' => $plasmaDonorsPaginator->currentPage(),
            'lastPage' => $plasmaDonorsPaginator->lastPage(),
        ];

        return response()->json($transformedData);
    }
}
