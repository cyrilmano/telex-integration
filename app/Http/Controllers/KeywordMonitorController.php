<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

use App\Models\KeywordMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KeywordMonitorController extends Controller
{
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'message' => 'required|string',
            'sender' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $keywords = ["urgent", "meeting", "bug", "deadline"];
        $message = strtolower($request->input('message'));

        foreach ($keywords as $keyword) {
            if (str_contains($message, strtolower($keyword))) {
                KeywordMessage::create([
                    'keyword' => $keyword,
                    'message' => $request->input('message'),
                    'sender' => $request->input('sender'),
                    'received_at' => now(),
                ]);
            }
        }

        return response()->json(["message" => "Processed"], 200);
    }

    public function sendSummary()
    {
        $summary = KeywordMessage::whereDate('received_at', today())
            ->orderBy('received_at')
            ->get()
            ->groupBy('keyword');

        $summaryMessage = "Daily Keyword Summary \n\n";
        foreach ($summary as $keyword => $messages) {
            $summaryMessage .= "*$keyword*\n";
            foreach ($messages as $msg) {
                $summaryMessage .= "- {$msg->message} ({$msg->sender})\n";
            }
            $summaryMessage .= "\n";
        }

        $webhookUrl = env('TELEX_WEBHOOK_URL');
        $this->sendToTelex($webhookUrl, $summaryMessage);

        return response()->json(["message" => "Summary sent"], 200);
    }

    private function sendToTelex($url, $message)
    {
        try {
            $response = Http::post($url, [
                'event_name' => 'Daily Summary',
                'message' => $message,
                'status' => 'success',
                'username' => 'your-username'
            ]);

            if ($response->failed()) {
                Log::error("Telex webhook error: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Telex webhook exception: " . $e->getMessage());
        }
    }

    public function getJsonData()
    {
        // Path to the JSON file
        $path = public_path('telex-integration.json');

        // Check if file exists
        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        return response()->json($data);
    }

    /* private function sendToTelex($url, $message)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $client->post($url, [
                'json' => ['text' => $message]
            ]);
        } catch (\Exception $e) {
            Log::error("Telex webhook error: " . $e->getMessage());
        }
    } */
}
