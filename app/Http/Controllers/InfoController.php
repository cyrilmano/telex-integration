<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InfoController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Welcome'
        ], 200);
    }
    public function getInfo()
    {
        return response()->json([
            'email' => 'your-email@example.com',
            'current_datetime' => Carbon::now()->toIso8601String(),
            'github_url' => 'https://github.com/yourusername/hng12-stage0-api',
        ], 200);
    }
}
