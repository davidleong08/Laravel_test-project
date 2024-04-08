@if($bodyCondition && $bodyCondition->id)
    <form action="{{ route('body-conditions.update', ['id' => $bodyCondition->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- 表单元素，如身高、体重等 -->
        <input type="number" name="height" value="{{ old('height', $bodyCondition->height) }}" required>
        <input type="number" name="weight" value="{{ old('weight', $bodyCondition->weight) }}" required>
        <input type="text" name="sleep_quality" value="{{ old('sleep_quality', $bodyCondition->sleep_quality) }}">
        <input type="text" name="health_goals" value="{{ old('health_goals', $bodyCondition->health_goals) }}">
        <input type="text" name="allergies" value="{{ old('allergies', $bodyCondition->allergies) }}">

        <!-- 其他表单字段 -->
        <button type="submit">Submit</button>
    </form>
@else
    <!-- 处理 $bodyCondition 为空的情况，例如显示错误消息或重定向 -->
@endif
