{{-- resources/views/body_conditions/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Personalize Your Soup Recommendations</h1>

    {{-- 显示成功或错误信息 --}}
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    {{-- 表单新增或编辑 body conditions --}}
    <form method="post" action="{{ route('body-conditions.store-or-update') }}">
        @csrf
        {{-- 邮箱输入字段 --}}
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        {{-- 身高输入字段 --}}
        <div class="form-group">
            <label for="height">Height (cm)</label>
            <input type="number" class="form-control" id="height" name="height" placeholder="Enter your height" required>
        </div>
        {{-- 体重输入字段 --}}
        <div class="form-group">
            <label for="weight">Weight (kg)</label>
            <input type="number" class="form-control" id="weight" name="weight" placeholder="Enter your weight" required>
        </div>
        {{-- 睡眠质量选择字段 --}}
        <div class="form-group">
            <label for="sleep_quality">Sleep Quality</label>
            <select class="form-control" id="sleep_quality" name="sleep_quality">
                <option value="good">Good</option>
                <option value="poor">Poor</option>
            </select>
        </div>
        {{-- 健康目标输入字段 --}}
        <div class="form-group">
    <label for="health_goals">Health Goals</label>
    <select class="form-control" id="health_goals" name="health_goals">
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
            <input type="text" class="form-control" id="allergies" name="allergies" placeholder="List any allergies">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>

<div class="row">
    <div class="col-md-12">
        <h2>Body Record</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Height</th>
                    <th>Weight</th>
                    <th>Sleep Quality</th>
                    <th>Health Goals</th>
                    <th>Allergies</th>
                    <th>BMI</th>
                    <th>Created At</th>
                    <th>Body Status</th>
                    <th>Recommendation</th>
                </tr>
            </thead>
            <tbody>
                {{-- 遍历所有的body_conditions记录 --}}
                @foreach ($bodyConditions as $bodyCondition)
                    <tr>
                        <td>{{ $bodyCondition->email }}</td>
                        <td>{{ $bodyCondition->height }}</td>
                        <td>{{ $bodyCondition->weight }}</td>
                        <td>{{ $bodyCondition->sleep_quality }}</td>
                        <td>{{ $bodyCondition->health_goals }}</td>
                        <td>{{ $bodyCondition->allergies }}</td>
                        <td>{{ $bodyCondition->bmi }}</td>
                        <td>{{ $bodyCondition->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                @php
                    $bodyStatus = $bodyCondition->bodyStatus;
                @endphp
                @if ($bodyStatus == 'underweight')
                    <span class="text-warning">Underweight</span>
                @elseif ($bodyStatus == 'healthy')
                    <span class="text-success">Healthy</span>
                @elseif ($bodyStatus == 'overweight')
                    <span class="text-warning">Overweight</span>
                @else
                    <span class="text-danger">Obese</span>
                @endif
            </td>
            {{-- 根据体重状况推荐汤水 --}}
            <td>
                @if ($bodyStatus == 'underweight')
                    <p>Recommendation: Nourishing soup to gain weight.</p>
                @elseif ($bodyStatus == 'healthy')
                    <p>Recommendation: Balanced soup for maintaining health.</p>
                @elseif ($bodyStatus == 'overweight')
                    <p>Recommendation: Light soup for weight loss.</p>
                @else
                    <p>Recommendation: Detox soup for weight control.</p>
                @endif
            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(session('recommendedSoups'))
    <div class="row">
        <div class="col-md-12">
            <h2>Recommended Soups</h2>
            @foreach(session('recommendedSoups') as $soup)
                <div class="col-sm-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <a href="{{ route('soup.show', $soup->id) }}">
                            <img class="card-img-top" src="{{ $soup->image_url }}" alt="{{ $soup->name }}">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $soup->name }}</h5>
                            <p class="card-text">{{ $soup->description }}</p>
                            <!-- You can add more soup details here -->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

    </div>
</div>



@endsection
