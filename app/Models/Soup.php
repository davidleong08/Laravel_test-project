<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soup extends Model
{
    // 確保你的模型名稱和文件名匹配，並且命名空間正確

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'image_url', 'season', 'benefits', 'suitable_constitution', 'unsuitable_for', 'Cookingmethods'
    ];

    public function conditions()
    {
        return $this->belongsToMany(Condition::class);
    }

    public static function filter($criterias)
    {
            $soups=ConditionSoup::select('soup_id')->whereIn('condition_id',$criterias)->groupBy('soup_id')->get();

        return $soups;
        // $myConditions=Condition::
        //     orWhere('id', $criteria[0])
        //     ->orWhere('id', $criteria[1])
        //     ->orWhere('id', $criteria[2])
        //     ->get();
        // dd($myConditions);
        // dd($myConditions[2]->soups);
        // dd($myConditions->soups);
        // $query = self::query();

        // foreach ($criteria as $key => $value) {
        //     if (!empty($value)) {
        //         $query->where($key, $value);
        //     }
        // }

        // return $query->get();
    }


}
