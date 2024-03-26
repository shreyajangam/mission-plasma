<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlasmaRequest;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class PlasmaRequestController extends Controller
{
    // Show the plasma request form
    public function showRequestForm()
    {
        return view('plasmaRequest.request_form');
    }

    // Store the plasma request form data
    public function storeRequestForm(Request $request)
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

            // Create a new plasma request record
            $requester = new PlasmaRequest();
            $requester->name = $request->input('name');
            $requester->gender = $request->input('gender');
            $requester->age = $request->input('age');
            $requester->blood_group = $request->input('blood_group');
            $requester->covid_positive_date = $request->input('covid_positive_date');
            $requester->covid_negative_date = $request->input('covid_negative_date');
            $requester->country_id = $request->input('country');
            $requester->state_id = $request->input('state');
            $requester->city_id = $request->input('city');
            $requester->phone_number = $request->input('phone_number');
            $requester->save();

            // Commit the transaction
            DB::commit();

            // Log the submission of requester details
            Log::info('Requester details submitted successfully', ['requester_id' => $requester->id]);

            return response()->json(['message' => 'Requester details submitted successfully']);
        } catch (Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();

            // Log the error
            Log::error('Error submitting requester details', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'An error occurred while submitting requester details'], 500);
        }
    }

    // Show the plasma donors list
    public function showDonorsList()
    {
        return view('plasmaDonor.donors_list');
    }

    // Get the list of plasma requests with filters and pagination
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10); // Default page size is 10
        $requests = PlasmaRequest::query();

        // Apply filters if provided in the request
        if ($request->filled('country')) {
            $requests->whereHas('country', function ($query) use ($request) {
                $query->where('name', $request->input('country'));
            });
        }
        if ($request->filled('state')) {
            $requests->whereHas('state', function ($query) use ($request) {
                $query->where('name', $request->input('state'));
            });
        }
        if ($request->filled('blood_group')) {
            $requests->where('blood_group', $request->input('blood_group'));
        }

        // Add a filter for valid requests based on Covid-19 positive date
        $validDate = now()->subDays(14)->toDateString();
        $requests->where('covid_positive_date', '<=', $validDate);

        // Order by the latest timestamp (updated_at or created_at)
        $requests->latest();

        // Paginate the results
        $plasmaRequestsPaginator = $requests->paginate($pageSize);

        // Transform the items in the paginator for response
        $transformedRequests = [];
        foreach ($plasmaRequestsPaginator->items() as $request) {
            $transformedRequests[] = [
                'id' => $request->id,
                'name' => $request->name,
                'gender' => $request->gender,
                'age' => $request->age,
                'blood_group' => $request->blood_group,
                'covid_positive_date' => $request->covid_positive_date,
                'covid_negative_date' => $request->covid_negative_date,
                'country' => $request->country['name'],
                'state' => $request->state['name'],
                'city' => $request->city['name'],
                'phone_number' => $request->phone_number,
            ];
        }

        // Create a new array that includes the transformed requests and pagination information
        $transformedData = [
            'data' => $transformedRequests,
            'total' => $plasmaRequestsPaginator->total(),
            'perPage' => $plasmaRequestsPaginator->perPage(),
            'currentPage' => $plasmaRequestsPaginator->currentPage(),
            'lastPage' => $plasmaRequestsPaginator->lastPage(),
        ];

        return response()->json($transformedData);
    }
}
