{{-- resources/views/body_conditions/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Personalize Your Soup Recommendations</h1>
    {{ $soups }}
    {{-- 显示成功或错误信息 --}}
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    {{-- 表单新增或编辑 body conditions --}}
    <form method="post" action="{{ route('body-conditions.update') }}">
        @csrf

        </div>
        {{-- 身高输入字段 --}}
        <input type="hidden" name="id" value="{{$bodyConditions->id}}">
        <div class="form-group">
            <label for="height">Height (cm)</label>
            <input type="number" class="form-control" id="height" name="height" value="{{$bodyConditions->height}}" placeholder="Enter your height" required>
        </div>
        {{-- 体重输入字段 --}}
        <div class="form-group">
            <label for="weight">Weight (kg)</label>
            <input type="number" class="form-control" id="weight" name="weight" value="{{$bodyConditions->weight}}" placeholder="Enter your weight" required>
        </div>
        {{-- 睡眠质量选择字段 --}}
        <div class="form-group">
            <label for="sleep_quality">Sleep Quality</label>
            <select class="form-control" id="sleep_quality" name="sleep_quality"  value="{{$bodyConditions->sleep_quality}}">
                <option value="good">Good</option>
                <option value="poor">Poor</option>
            </select>
        </div>
        {{-- 健康目标输入字段 --}}
        <div class="form-group">
    <label for="health_goals">Health Goals</label>
    <select class="form-control" id="health_goals" name="health_goals" value="{{$bodyConditions->health_goals}}">
        <option value="">Select your health goal</option>
        <option value="boost_immunity">Boost Immunity</option>
        <option value="improve_digestion">Improve Digestion</option>
        <option value="increase_energy">Increase Energy</option>
        <option value="reduce_stress">Reduce Stress</option>
        <!-- ... 其他預定義的健康目標 ... -->
    </select>
</div>
        {{-- 过敏源输入字段 --}}
        <div class="form-group">
            <label for="allergies">Allergies</label>
            <input type="text" class="form-control" id="allergies" name="allergies" value="{{$bodyConditions->allergies}}" placeholder="List any allergies">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>




@endsection
