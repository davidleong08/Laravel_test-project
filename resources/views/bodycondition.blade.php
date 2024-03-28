@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Filter Body Conditions</h2>
    <form action="{{ url('body-conditions/filter') }}" method="GET">
        <div class="mb-3">
            <label for="body_type" class="form-label">Body Type</label>
            <select class="form-select" id="body_type" name="body_type">
                <option value="">Select Body Type</option>
                <option value="fat">Fat</option>
                <option value="thin">Thin</option>
                <option value="moderate">Moderate</option>
            </select>
        </div>
        
        {{-- 在此添加更多的篩選器字段 --}}
        
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>
@endsection