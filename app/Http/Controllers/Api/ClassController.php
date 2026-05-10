<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ClassController extends Controller
{
    public function index(): JsonResponse
    {
        $classes = Cache::remember('all_classes', 3600, function () {
            return SchoolClass::select('id', 'name')
                ->orderBy('name')
                ->get();
        });
        return response()->json($classes);
    }
}
