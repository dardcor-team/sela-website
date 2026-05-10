<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function index(): JsonResponse
    {
        $courses = Course::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
    }
}
