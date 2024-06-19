<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

trait JsReportService
{
    protected function print($template, $data)
    {
        $JS_BASE_URL = env('JS_BASE_URL', 'http://localhost:5488');
        $JS_USERNAME = env('JS_USERNAME', 'pisey');
        $JS_PASSWORD = env('JS_PASSWORD', 'CICDnano');
        $result = new \stdClass();
        try {
            $body = [
                "template" => [
                    "name" => $template,
                ],
                "data" => $data,
            ];
            $response = Http::withBasicAuth($JS_USERNAME, $JS_PASSWORD)->post($JS_BASE_URL . '/api/report', $body);
            if (!$response->successful()) {
                $result->error = 'Report generation failed with status ' . $response->status();
            }
            $result->data = base64_encode($response->body());
        } catch (\Exception $e) {
            $result->error = $e->getMessage();
            $result->data  = $e;
        }
        return $result;
    }
}
