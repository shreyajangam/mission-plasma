<?php

namespace App\Http\Controllers;

use App\Models\PlasmaDonor;
use App\Models\PlasmaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\State;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total count of donors and requests
        $totalDonors = PlasmaDonor::count();
        $totalRequests = PlasmaRequest::count();

        // Get counts of donors in each state
        $donorStateCounts = PlasmaDonor::select('state_id', DB::raw('count(*) as count'))
            ->groupBy('state_id')
            ->get()
            ->toArray();

        // Get state names for donor counts
        $donorStates = [];
        foreach ($donorStateCounts as $donorState) {
            $state = State::find($donorState['state_id']);
            if ($state) {
                $donorStates[] = [
                    'state_name' => $state->name,
                    'count' => $donorState['count']
                ];
            }
        }

        // Get counts of requests in each state
        $requestStateCounts = PlasmaRequest::select('state_id', DB::raw('count(*) as count'))
            ->groupBy('state_id')
            ->get()
            ->toArray();

        // Get state names for request counts
        $requestStates = [];
        foreach ($requestStateCounts as $requestState) {
            $state = State::find($requestState['state_id']);
            if ($state) {
                $requestStates[] = [
                    'state_name' => $state->name,
                    'count' => $requestState['count']
                ];
            }
        }

        // Log the dashboard data retrieval
        Log::info('Dashboard data retrieved', ['total_donors' => $totalDonors, 'total_requests' => $totalRequests]);

        // Pass data to the dashboard view
        return view('dashboard.dashboard', compact('totalDonors', 'totalRequests', 'donorStates', 'requestStates'));
    }

    // Redirect to the plasma request form
    public function redirectToRequestForm()
    {
        // Log the redirection to the plasma request form
        Log::info('Redirecting to plasma request form');

        return Redirect::route('request-plasma');
    }

    // Redirect to the plasma donor form
    public function redirectToDonorForm()
    {
        // Log the redirection to the plasma donor form
        Log::info('Redirecting to plasma donor form');

        return redirect()->route('donor-form');
    }
}
