<?php

namespace App\Http\Controllers;

use App\Services\BotService;
use Illuminate\Http\Response;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use GuzzleHttp\Client;

class TestTelegramController extends Controller
{
    use BotService;

    public function message()
    {
        $chat_id = env('TELEGRAM_BOT_CHAT_ID');
        $text = "Hello Kon Papa";
        $parse_mode = "HTML";
        $result = $this->sendMessage($chat_id, $text, $parse_mode);
        if (isset($result->error)) {
            return response([
                'status'  => 'fail',
                'message' => $result->error
            ], Response::HTTP_BAD_REQUEST);
        }
        return response([
            'status'  => 'success',
            'data'    => $result->data
        ], Response::HTTP_OK);
    }

    public function photo()
    {
        $chat_id  = env('TELEGRAM_BOT_CHAT_ID');
        $file_url = "http://file.edvise.asia/static/edvise/user/avatar.png"; //not work with localhot
        $caption  = "Hello Kon Papa";
        $parse_mode = "HTML";
        $result = $this->sendPhoto($chat_id, $file_url, $caption, $parse_mode);
        if (isset($result->error)) {
            return response([
                'status'  => 'fail',
                'message' => $result->error,
                'error'   => $result->data
            ], Response::HTTP_BAD_REQUEST);
        }
        return response([
            'status'  => 'success',
            'data'    => $result->data
        ], Response::HTTP_OK);
    }

    public function send()
    {
        // Replace 'YOUR_BOT_TOKEN' with your actual bot token
        $botToken = '7035154742:AAGDl2spTS1p8quc_2vdqyQKyUXNz0SMkKs';

        // Set the chat ID where you want to send the message
        $chatId = '573818129';

        // Set the message text
        $message = 'Hello from your bot!';

        // Create a Guzzle HTTP client instance
        $client = new Client();

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
    }
}
