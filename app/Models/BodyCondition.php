<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class BodyCondition extends Model
{

    use HasFactory;

    protected $fillable = [
        'email', 'height', 'weight', 'sleep_quality', 'health_goals', 'allergies', 'bmi','body_status',
    ];

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include body conditions that match the given criteria.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $criteria
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // 在BodyCondition模型中
    public function scopeFilter($query, $criteria)
    {
        foreach ($criteria as $key => $value) {
            if (!empty($value)) {
                $query->where($key, $value);
            }
        }
        return $query;
    }
    public function calculateBMI()
{
    // 确保身高(height)和体重(weight)字段已经有值
    if ($this->height && $this->weight) {
        // 将身高从厘米转换为米
        $heightInMeters = $this->height / 100;
        // 计算BMI
        $this->bmi = $this->weight / ($heightInMeters * $heightInMeters);
        // 保存BMI值
        $this->save();
    }
}

public function updateBodyStatus()
{
    // 根据bmi或其他逻辑更新body_status字段
    if ($this->bmi < 18.5) {
        $this->body_status = 'HUW';
    } elseif ($this->bmi >= 18.5 && $this->bmi < 25) {
        $this->body_status = 'HHH';
    } elseif ($this->bmi >= 25) {
        $this->body_status = 'HOW';
    }

    // 可能还有更多的条件和逻辑
}

public function recommendedSoups()
{
    // 假设我们根据体质和健康目标来推荐汤水
    $query = Soup::query();

    if ($this->bmi < 18.5) {
        $query->where('suitable_constitution', 'like', '%underweight%');
    } elseif ($this->bmi > 25) {
        $query->where('suitable_constitution', 'like', '%overweight%');
    }

    // 假设 'health_goals' 字段存储的是用户的健康目标
    if (!empty($this->health_goals)) {
        $query->where('benefits', 'like', "%{$this->health_goals}%");
    }

    return $query->get();
}


public function conditions()
    {
        return $this->hasManyThrough(Condition::class, ConditionSoup::class, 'condition_id', 'id');
    }

}
