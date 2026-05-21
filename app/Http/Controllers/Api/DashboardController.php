<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request, $user_id)
    {
        $search = $request->search;

        $data = $this->dashboardService->getDashboard($user_id, $search);

        return response()->json($data);
    }
}
