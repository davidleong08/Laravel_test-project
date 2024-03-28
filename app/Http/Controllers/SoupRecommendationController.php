<?php
// app/Http/Controllers/SoupRecommendationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soup;
use App\Models\BodyCondition;

class SoupRecommendationController extends Controller
{
    public function recommend(Request $request)
    {
        // 從請求中獲取用戶ID並查找相應的身體狀況
        $userId = $request->input('user_id');
        $bodyCondition = BodyCondition::where('user_id', $userId)->first();

        // 獲取當前的季節，這個範例中我們假設它是從請求中傳遞過來的
        $currentSeason = $request->input('season');

        // 構建查詢以獲得推薦的湯水
        $query = Soup::query();

        if ($bodyCondition) {
            // 如果用戶需要改善消化，則過濾出有助於消化的湯水
            if ($bodyCondition->health_goals === 'improve_digestion') {
                $query->where('benefits', 'LIKE', '%strengthen the spleen and qi%');
            }

            // 如果用戶的BMI較高，則推薦低脂的湯水
            if ($bodyCondition->bmi > 25) {
                $query->where('benefits', 'LIKE', '%low-fat%');
            }
        }

        // 根據季節過濾湯水
        $query->where('season', $currentSeason);

        // 假設'allergies'欄位是一個以逗號分隔的字符串，包含用戶過敏的食材
        if ($bodyCondition && $bodyCondition->allergies) {
            $allergies = explode(',', $bodyCondition->allergies);
            foreach ($allergies as $allergy) {
                $query->where('description', 'NOT LIKE', '%' . $allergy . '%');
            }
        }

        // 獲取推薦的湯水
        $recommendedSoups = $query->get();

        return response()->json($recommendedSoups);
    }
}
