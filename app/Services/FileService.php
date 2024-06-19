<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

trait FileService
{
    protected function uploadBase64($file, $folder)
    {
        $body = [
            'key'       => env('FILE_KEY'),
            'folder'    => $folder,
            'image'     => $file
        ];
        $response = Http::withBasicAuth(env('FILE_USERNAME'), env('FILE_PASSWORD'))
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post(env('FILE_BASE_URL') . '/api/file/upload-base64', $body);
        $response = json_decode($response);
        /**
         * stdClass is a built-in PHP class that is very basic and stands for "standard class".
         * It's used to create generic objects and is useful when you need a flexible object to store data temporarily.
         */
        $result = new \stdClass();
        $response->statusCode == 200 ? $result->file = $response->data : $result->error = $response->message;
        return $result;
    }

    protected function uploadSingleFile($file, $folder)
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
            ->attach($body)->post(env('FILE_BASE_URL') . '/api/file/upload-single');
        $response = json_decode($response);
        $result = new \stdClass();
        $response->statusCode == 200 ? $result->file = $response->data : $result->error = $response->message;
        return $result;
    }

    protected function uploadMultiFiles($files, $folder)
    {
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
        $response = Http::withBasicAuth(env('FILE_USERNAME'), env('FILE_PASSWORD'))
            ->attach($body)->post(env('FILE_BASE_URL') . '/api/file/upload-multiple');
        $response = json_decode($response);
        $result = new \stdClass();
        $response->statusCode == 200 ? $result->files = $response->data : $result->error = $response->message;
        return $result;
    }
}
