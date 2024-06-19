<?php

namespace App\CamCyber;

// ===================================================>> Core Library
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class FileUpload {

    public static function uploadBase64($file, $folder, $fileName)
    {

        $body = [
            'key'       => env('FILE_KEY'),
            'folder'    => $folder,
            'image'     => $file
        ];

        // Need to create folder before storing images  'dmd'     q1w2e3
        $response = Http::withBasicAuth(env('FILE_USERNAME'), env('FILE_PASSWORD'))
        ->withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->post(env('FILE_BASE_URL') . '/api/file/upload-base64', $body);

        $response = json_decode($response, true);

        return $response;

        if ($response['statusCode'] != 200) {
            return ['uri'=>''];
        }else{
            return  $response['data'];
        }

    }

    public static function uploadSingleFile($file, $folder, $fileName)
    {
        $body = [
            [
                'name'      => 'key',
                'contents'  => env('FILE_KEY'),
            ],
            [
                'name'      => 'folder',
                'contents'  => $folder,
            ],
            [
                'name'      => 'file',
                'contents'  => fopen($file->getRealPath(), 'r'),
                'filename'  => $file->getClientOriginalName()
            ]
        ];

        $response = Http::withBasicAuth(env('FILE_USERNAME'), env('FILE_PASSWORD'))
        ->attach($body)
        ->post(env('FILE_BASE_URL'). '/api/file/upload-single');

        $response = json_decode($response, true);

        if ($response['statusCode'] != 200) {

            return [
                'statusCode'    => Response::HTTP_BAD_REQUEST,
                'error'         => $response['message']
            ];
        }else{

            return [
                'statusCode'    => 200,
                'uri'           => $response['data']['uri'],
                'size'          => $response['data']['size']
            ];
        }


    }

    public static function uploadMultiFiles($files, $folder, $fileNames)
    {
        // ===>> Prepare Data
        $body = [
            [
                'name'      => 'key',
                'contents'  => env('FILE_KEY'),
            ],
            [
                'name'      => 'folder',
                'contents'  => $folder,
            ]
        ];

        foreach ($files as $file) {

            array_push($body, [
                'name'      => 'files',
                'contents'  => fopen($file->getRealPath(), 'r'),
                'filename'  => $file->getClientOriginalName()
            ]);

        }

        // ===>> Sending to File Service
        $response = Http::withBasicAuth(env('FILE_USERNAME'), env('FILE_PASSWORD'))
        ->attach($body)
        ->post(env('FILE_BASE_URL') . '/api/file/upload-multiple');

        $response = json_decode($response, true);

        // ===>> Prepare Response
        if ($response['statusCode'] != 200) {
            return response()->json([
                'statusCode'    => Response::HTTP_BAD_REQUEST,
                'error'         => $response['message']
            ], Response::HTTP_BAD_REQUEST);
        }

        return $response['data'];

    }

}
