<?php

// BotRegisteration.php
namespace App\CamCyber\Bot;

use App\Http\Controllers\Controller;
use \Telegram\Bot\FileUpload\InputFile;  // Make sure to use the correct namespace
use Telegram\Bot\Laravel\Facades\Telegram;

class BotRegisteration extends Controller
{

    // ...

    // =========================================================================================>> Register
    public static function newRegisteration($user, $services)
    {
        if ($user) {
            $url = 'http://file.edvise.asia/';
            $chatID = env('REGISTERATION_UAT_CHANNEL_CHAT_ID');

            // Concatenate the image URL with the user's avatar
            if ($user->avatar) {
                $imageUrl = $url . $user->avatar;
            } else {
                $imageUrl = $url . 'static/edvise/user/avatar.png';
            }

            $captions = "- ប្រភេទសេវាកម្ម : " . "\n";

            foreach ($services as $service) {
                $captions .= str_repeat(' ', 4) .' + ' . $service['en_name'] . "\n";
            }


            // Use InputFile::create to handle the photo
            $photo = InputFile::create($imageUrl);

            // Send photo with caption
            $res = Telegram::sendPhoto([
                'chat_id' => $chatID,
                'photo' => $photo,
                'caption' => '
- ឈ្មោះអតិថិជន: ' . $user->name . '
- លេខទូរស័ព្ទ: ' . $user->phone . "\n"
                    . $captions,

                'parse_mode' => 'HTML'
            ]);

            return $res;
        }
    }
}
