<?php

namespace App\MACA;

use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use Nexmo;
use GuzzleHttp\Client;
class SMS extends Controller
{
    
    // send sms
    public static function sendSMS($phone, $message){

        if(preg_match("/(^[0][0-9].{7}$)|(^[0][0-9].{8}$)/", $phone)){
            $phone = '855'.substr($phone, 1);
        }
      
         $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cloudapi.plasgate.com/api/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('to' => $phone,'sender' => 'SMS Info','content' => $message,'username' => 'bopisey1@gmail.com','password' => 'E)&g*:aK-uwe09l;08sLL'),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        return $response;
    }


 
}
