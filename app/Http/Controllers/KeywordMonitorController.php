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
    public function getDataFromTelex(Request $request)
    {
        Log::info('Incoming request:', $request->all());

        $validator = Validator::make($request->all(), [
            'channel_id' => 'required|string',
            'message' => 'required|string',
            'settings' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $expectedChannelId = env('TELEX_CHAT_CHANNEL_ID');
        if ($request->input('channel_id') !== $expectedChannelId) {
            return response()->json(['error' => 'Invalid channel ID'], 403);
        }

        $message = strip_tags(strtolower($request->input('message')));

        $keywords = explode(',', env('TELEX_KEYWORDS', ''));

        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                KeywordMessage::create([
                    'keyword' => $keyword,
                    'message' => $message,
                    'sender' => 'Anonymous',
                    'received_at' => now(),
                ]);
            }
        }

        return response()->json(["message" => "Data received and processed"], 200);
    }

    public function sendSummary(Request $request)
    {
        Log::info('Sending summary:');
        Log::info('Incoming request:', $request->all());

        $validator = Validator::make($request->all(), [
            'channel_id' => 'required|string',
            'return_url' => 'required|url',
            'settings' => 'required|array',
            'settings.*.default' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $channelId = $request->input('channel_id');
        $returnUrl = $request->input('return_url');
        $settings = $request->input('settings');

        $expectedChannelId = env('TELEX_SUMMARY_CHANNEL_ID');
        $expectedInterval = env('TELEX_DEFAULT_INTERVAL');

        if ($channelId !== $expectedChannelId) {
            return response()->json(['error' => 'Invalid Summary Channel ID'], 403);
        }

        $defaultInterval = $settings[0]['default'] ?? null;
        if ($defaultInterval !== $expectedInterval) {
            return response()->json(['error' => 'Invalid interval setting'], 403);
        }

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

        $webhookUrl = env('TELEX_SUMMARY_CHANNEL_WEBHOOK_URL');
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
