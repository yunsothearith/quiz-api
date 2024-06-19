<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

trait BotService
{

    protected function sendMessage($chat_id, $text, $parse_mode = 'HTML' | 'MarkdownV2')
    {
        $botToken  = env('TELEGRAM_BOT_TOKEN');
        $result = new \stdClass();
        try {
            $body = [
                'chat_id'       => $chat_id,
                'text'          => $text,
                'parse_mode'    => $parse_mode
            ];
            $response = Http::withOptions(['verify' => false])->post("https://api.telegram.org/bot$botToken/sendMessage", $body);
            if (!$response->successful()) {
                $result->error = 'Sending telegram failed with status ' . $response->status();
            }
            $result->data = json_decode($response->body());
        } catch (\Exception $e) {
            $result->error = $e->getMessage();
            $result->data  = $e;
        }
        return $result;
    }

    protected function sendPhoto($chat_id, $file_url, $caption, $parse_mode = 'HTML' | 'MarkdownV2')
    {
        $botToken  = env('TELEGRAM_BOT_TOKEN');
        $result = new \stdClass();
        try {
            $body = [
                'chat_id'       => $chat_id,
                'photo'         => $file_url,
                'caption'       => $caption,
                'parse_mode'    => $parse_mode
            ];
            $response = Http::withOptions(['verify' => false])->post("https://api.telegram.org/bot$botToken/sendPhoto", $body);
            if (!$response->successful()) {
                $result->error = 'Sending telegram failed with status ' . $response->status();
            }
            $result->data  = json_decode($response->body());
        } catch (\Exception $e) {
            $result->error = $e->getMessage();
            $result->data  = $e;
        }
        return $result;
    }

    public function send($chat_id, $caption, $parse_mode = 'HTML' | 'MarkdownV2')
{
    try {
        // Replace 'YOUR_BOT_TOKEN' with your actual bot token
        $botToken = '7035154742:AAGDl2spTS1p8quc_2vdqyQKyUXNz0SMkKs';

        // Set the chat ID where you want to send the message
        $chatId = $chat_id;

        // Set the message text
        $message = $caption;

        // Create a Guzzle HTTP client instance
        $client = new \GuzzleHttp\Client();

        // Send a POST request to the Telegram Bot API to send the message
        $response = $client->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
            ],
        ]);

        // Get the response body
        $body = $response->getBody();

        // Process the response as needed
        // For example, you can decode the JSON response if applicable
        $responseData = json_decode($body, true);

        // Return a response
        return response()->json($responseData);
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        // Handle Guzzle HTTP client exceptions (e.g., 4xx, 5xx errors)
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();
        $errorBody = $response->getBody()->getContents();

        // Log or handle the error as needed
        // You can return a JSON response with the error details
        return response()->json([
            'error' => [
                'status_code' => $statusCode,
                'message' => $errorBody,
            ]
        ], $statusCode);
    } catch (\Exception $e) {
        // Handle other exceptions (e.g., network errors, unexpected errors)
        return response()->json([
            'error' => [
                'message' => $e->getMessage(),
            ]
        ], 500);
    }
}

}
