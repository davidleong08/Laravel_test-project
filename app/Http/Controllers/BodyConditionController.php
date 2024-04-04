<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\BodyCondition;
use App\Models\Condition;
use App\Models\Soup;


class BodyConditionController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        //dd($user->bodyCondition);
        $bodyConditions = $user->bodyConditions->first();
        $sleepQuality = $bodyConditions->sleep_quality;
        $healthGoals = $bodyConditions->health_goals;
        $bodyStatus = $bodyConditions->body_status;

        // dd($bodyConditions);
        $myConditions=Condition::
                        orWhere('level_value',$sleepQuality)
                        ->orWhere('level_value',$healthGoals)
                        ->orWhere('level_value',$bodyStatus)
                        ->pluck('id')->toArray();
        //dd($myConditions);
        //使用者已經對應了
        $myConditions = [1,2,3];
        $soups=Soup::filter($myConditions);
        //$soups=Soup::with('conditions')->get();
        //dd($myConditions);
        //dd($myConditions);
         //dd($myConditions);

        //$bodyConditions = BodyCondition::all();
       // dd($bodyConditions);
        //dd($bodyConditions);
        //dd($bodyConditions);
        return view('body_conditions.index', [
            'bodyConditions'=>$bodyConditions,
            'soups'=>$soups
        ]);

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
        $bodyCondition->save();
        // $bodyCondition = BodyCondition::updateOrCreate(
        //     ['email' => $validatedData['email']],
        //     $validatedData
        // );

        // $bodyCondition->calculateBMI();
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

}
