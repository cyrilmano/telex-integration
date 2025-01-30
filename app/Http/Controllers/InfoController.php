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
            'email' => 'thankgoduchecyril@gmail.com',
            'current_datetime' => Carbon::now('UTC')->toIso8601String(),
            'github_url' => 'https://github.com/cyrilmano/hng12-backend',
        ], 200);
    }
}
