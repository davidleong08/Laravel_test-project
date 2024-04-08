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
        return $this->belongsToMany(Condition::class, 'condition_soup');
    }

    public static function filter($criterias)
    {
            $soups=ConditionSoup::select('soup_id')->whereIn('condition_id',$criterias)->groupBy('soup_id')->get();

        return $soups;

    }

    public function scopeFilter($query, $conditions)
    {
        return $query->whereHas('conditions', function ($q) use ($conditions) {
            $q->whereIn('condition_id', $conditions);
        });
    }

}
