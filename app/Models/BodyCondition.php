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
    if ($this->height && $this->weight) {
        $heightInMeters = $this->height / 100;
        $this->bmi = round($this->weight / ($heightInMeters ** 2), 2);

        // Set body_status based on the calculated BMI
        if ($this->bmi < 18.5) {
            $this->body_status = 'underweight';
        } elseif ($this->bmi >= 18.5 && $this->bmi <= 24.9) {
            $this->body_status = 'healthy';
        } elseif ($this->bmi >= 25 && $this->bmi <= 29.9) {
            $this->body_status = 'overweight';
        } else {
            $this->body_status = 'obese';
        }

        $this->save();
    }
}
// public function getBodyStatusAttribute()
// {

//     if ($this->bmi < 18.5) {
//         return 'underweight';
//     } elseif ($this->bmi >= 18.5 && $this->bmi <= 24.9) {
//         return 'healthy';
//     } elseif ($this->bmi >= 25 && $this->bmi <= 29.9) {
//         return 'overweight';
//     } else {
//         return 'obese';
//     }
// }
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

}
