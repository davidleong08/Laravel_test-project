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
        try {
            $user = Auth::user();
            if ($user === null) {
                return redirect()->route('login');
            }

            $bodyCondition = $user->bodyConditions()->first();
            if ($bodyCondition === null) {
                return redirect()->route('body-conditions.create');
            }
        $currentMonth = Carbon::now()->month;
        $currentSeason = $this->getSeasonByMonth($currentMonth);
        $userAllergies = $bodyCondition->allergies ? explode(',', $bodyCondition->allergies) : [];
        $query = Soup::query();
        $query->where(function ($query) use ($currentSeason) {
            $query->where('season', $currentSeason)
                ->orWhere('season', 'allseason');
        });
        foreach ($userAllergies as $allergy) {
            $allergy = strtolower(trim($allergy)); // 转换为小写并去除可能的空格
            $query->whereRaw('LOWER(name) NOT LIKE ?', ['%' . $allergy . '%']);
        }

        $soups = $query->get();



            if ($bodyCondition) {
                // 获取相匹配的条件ID
                $conditionIds = Condition::where('level_value', $bodyCondition->sleep_quality)
                    ->orWhere('level_value', $bodyCondition->health_goals)
                    ->orWhere('level_value', $bodyCondition->body_status)
                    ->pluck('id')->toArray(); // Convert the collection to a plain PHP array

                // 获取符合条件的湯品
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
                }else {
                // 没有找到用户的身体状况，返回一个空集合
                return view('body_conditions.index', [
                    'bodyCondition' => null,
                    'soups' => collect()
                ]);
            }
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
            $validatedData = $request->validate([
                'email' => 'required|email|unique:body_conditions,email,' . $request->id,
                'height' => 'required|numeric',
                'weight' => 'required|numeric',
                'sleep_quality' => 'nullable|string',
                'health_goals' => 'nullable|string',
                'allergies' => 'nullable|string',
            ]);

            $user = Auth::user();
            if ($user === null) {
                return redirect()->route('login');
            }

            $bodyConditionId = $request->id;

            if ($bodyConditionId) {
                // 更新操作
                $bodyCondition = BodyCondition::find($bodyConditionId);
                if (!$bodyCondition) {
                    return redirect()->back()->withErrors(['msg' => 'Body condition not found.']);
                }
            } else {
                // 新建操作
                $bodyCondition = new BodyCondition;
                $bodyCondition->user_id = $user->id; // 假设 BodyCondition 与 User 有关联
            }

            // 应用验证后的数据更新模型
            $bodyCondition->fill($validatedData);

            // 如果有计算BMI的逻辑
            if (method_exists($bodyCondition, 'calculateBMI')) {
                $bodyCondition->calculateBMI();
            }

            // 如果有更新body_status的逻辑
            if (method_exists($bodyCondition, 'updateBodyStatus')) {
                $bodyCondition->updateBodyStatus(); // 更新body_status
            }

            $bodyCondition->save();

            $message = $bodyConditionId ? 'Body condition updated successfully!' : 'Body condition created successfully!';
            return redirect()->route('body-conditions.index')->with('status', $message);
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

            $bodyCondition = BodyCondition::find($id);

            // 检查是否成功找到 BodyCondition 实例
            if ($bodyCondition) {
                // 使用验证后的数据更新模型
                $bodyCondition->update($validatedData);

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
        public function edit($id)
        {
            $bodyCondition = BodyCondition::findOrFail($id);
            return view('body_conditions.edit', compact('bodyCondition'));
        }

        public function create()
        {
        // 如果是創建新條件，可能不需要傳遞現有的 $bodyCondition 變量，
            // 而是應該初始化一個新的 BodyCondition 實例。
            $bodyCondition = new BodyCondition(); // 假設有一個空的模型實例

            // 這樣能保證在視圖中 $bodyCondition 變量已經定義，並且是空的模型實例，
            // 可以用於表單中的預設值。
            return view('body_conditions.create', compact('bodyCondition'));
        }


    }
    }
}
