<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function getGeminiKey()
    {
        $keysString = env('GEMINI_API_KEY', '');
        if (empty($keysString)) {
            return response()->json(['error' => 'No API keys configured'], 500);
        }

        $keys = array_filter(array_map('trim', explode(',', $keysString)));
        if (empty($keys)) {
            return response()->json(['error' => 'No valid API keys found'], 500);
        }

        $randomKey = $keys[array_rand($keys)];

        return response()->json([
            'key' => $randomKey
        ]);
    }
}
