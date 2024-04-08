<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\BodyCondition;
use App\Models\Condition;
use App\Models\Soup;
use Carbon\Carbon;

class BodyConditionController extends Controller
{

    public function index()
{
    $user = Auth::user();
    $bodyCondition = $user->bodyConditions()->first();
    $currentMonth = Carbon::now()->month;
    $currentSeason = $this->getSeasonByMonth($currentMonth);
    $conditionIds = $bodyCondition->conditions->pluck('id')->toArray();
    $soups = Soup::where(function ($query) use ($currentSeason) {
        $query->where('season', $currentSeason)
              ->orWhere('season', 'allseason');
    })->get();

    if ($bodyCondition) {
        $conditionIds = Condition::where('level_value', $bodyCondition->body_status)
            ->orwhere('level_value', $bodyCondition->health_goals)
            ->orwhere('level_value', $bodyCondition->sleep_quality)
            ->pluck('id')->toArray();


        // 获取符合条件的汤品
        $soups = Soup::where(function ($query) use ($currentSeason) {
            $query->where('season', $currentSeason)
                  ->orWhere('season', 'allseason');
        })->whereHas('conditions', function ($query) use ($conditionIds) {
            $query->whereIn('conditions.id', $conditionIds);
        })->get();

        // 计算匹配程度
        $matchingPercentage = $this->calculateMatchingPercentage($soups, $bodyCondition);

        // 根据匹配程度调整返回的视图
        return view('body_conditions.index', [
            'bodyCondition' => $bodyCondition,
            'soups' => $soups,
            'matchingPercentage' => $matchingPercentage
        ]);
    } else {
        // 没有找到用户的身体状况，返回一个空集合
        return view('body_conditions.index', [
            'bodyCondition' => null,
            'soups' => collect()
        ]);
    }
}

private function calculateMatchingPercentage($soups, $bodyCondition)
{
    $matchCount = 0;
    foreach ($soups as $soup) {
        if ($soup->conditions->contains('level_value', $bodyCondition->sleep_quality)) {
            $matchCount += 30;
        }
        if ($soup->conditions->contains('level_value', $bodyCondition->health_goals)) {
            $matchCount += 30;
        }
        if ($soup->conditions->contains('level_value', $bodyCondition->body_status)) {
            $matchCount += 40;
        }
    }

    return min(100, $matchCount); // 确保不超过100%
}

    /**
     * 根據篩選條件篩選身體條件。
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filter(Request $request)
    {
        $criteria = $request->all();
        $bodyConditions = BodyCondition::filter($criteria)->get();

        return view('body_conditions.index', ['bodyConditions' => $bodyConditions]);
    }

    public function save(Request $request)
    {

        return redirect()->route('body-conditions');
    }

    public function update(Request $request)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            // 'email' => 'required|email',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'sleep_quality' => 'nullable|string',
            'health_goals' => 'nullable|string',
            'allergies' => 'nullable|string'
        ]);

        $bodyCondition = BodyCondition::find($request->id);

        $bodyCondition->update($request->all());
         // 如果有计算BMI的逻辑
            if (method_exists($bodyCondition, 'calculateBMI')) {
                $bodyCondition->calculateBMI();
            }

            // 如果有更新body_status的逻辑
            if (method_exists($bodyCondition, 'updateBodyStatus')) {
                $bodyCondition->updateBodyStatus(); // 更新body_status
            }

        $bodyCondition->save();

        return redirect()->back();

        //return redirect()->route('body-conditions.index')->with('status', 'Data has been saved successfully!');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'email' => 'required|email|unique:body_conditions,email',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'sleep_quality' => 'nullable|string',
            'health_goals' => 'nullable|string',
            'allergies' => 'nullable|string',

        ]);

        $bodyCondition = BodyCondition::create($validatedData);
        $bodyCondition->calculateBMI();
        $user = Auth::user();
        $user->bodyConditions()->attach($bodyCondition->id);
        return redirect()->back()->with('status', 'Body condition submitted successfully!');

    }
    private function getSeasonByMonth($month)
{
    if ($month >= 3 && $month <= 5) {
        return 'spring';
    } elseif ($month >= 6 && $month <= 8) {
        return 'summer';
    } elseif ($month >= 9 && $month <= 11) {
        return 'autumn';
    } else {
        return 'winter';
    }
}

private function getSoupsBySeason($season)
{
    // 這裡是示例，你需要根據自己的數據庫結構進行調整
    return Soup::where('season', $season)->orWhere('season', 'allseason')->get();
}

}
