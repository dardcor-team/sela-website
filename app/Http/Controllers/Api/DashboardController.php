<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Cache;

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

        $cacheKey = "dashboard_{$user_id}" . ($search ? '_' . md5($search) : '');

        $data = Cache::tags(["dashboard_{$user_id}"])->remember($cacheKey, 300, function () use ($user_id, $search) {
            return $this->dashboardService->getDashboard($user_id, $search);
        });

        return response()->json($data);
    }
}
