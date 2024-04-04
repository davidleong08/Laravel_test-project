<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soup;
use App\Models\Condition;
use Illuminate\Support\Facades\Auth;

class SoupRecommendationController extends Controller
{
    /**
     * 根據用戶的條件推薦湯品。
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function recommend(Request $request)
    {
        // 從前端獲取條件的 level_value 值
        $sleepQuality = $request->input('sleep_quality');
        $healthGoals = $request->input('health_goals');
        $bodyStatus = $request->input('body_status');

        // 根據這些條件找到對應的 Condition IDs
        $conditionIds = Condition::query()
            ->orWhere('level_value', $sleepQuality)
            ->orWhere('level_value', $healthGoals)
            ->orWhere('level_value', $bodyStatus)
            ->pluck('id')
            ->toArray();

        // 根據條件IDs找到對應的湯品
        $soups = Soup::whereHas('conditions', function ($query) use ($conditionIds) {
            $query->whereIn('condition_id', $conditionIds);
        })->get();

        // 如果沒有找到合適的湯，返回一個空陣列
        if ($soups->isEmpty()) {
            return response()->json(['message' => 'No soups found for the provided conditions', 'soups' => []]);
        }

        // 將推薦的湯品返回給前端
        return response()->json(['message' => 'Soup recommendations based on conditions', 'soups' => $soups]);
    }
}
