<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getWeather(Request $request, $city)
    {
        $city = urlencode($city);
        $apiKey = env('API_NINJAS_KEY'); // 將您的 API 密鑰存儲在 .env 文件中

        $response = Http::withHeaders([
            'X-Api-Key' => $apiKey
        ])->get("https://api.api-ninjas.com/v1/weather?city={$city}");

        if ($response->successful()) {
            return $response->body();
        } else {
            // 處理錯誤情況
            return response()->json([
                'error' => $response->status(),
                'message' => $response->body()
            ], $response->status());
        }
    }
}
