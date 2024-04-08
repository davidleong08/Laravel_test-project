{{-- 假设 season 是一个字符串字段 --}}
<div class="col-md-4 soup-item" data-season="{{ strtolower($soup->season) }}">
    <div class="card mb-4">
        <img src="{{ $soup->image_url }}" alt="{{ $soup->name }}" class="card-img-top img-fluid">
        <div class="card-body">
            <h5 class="card-title">{{ $soup->name }}</h5>
            <a href="{{ url('/soup/'.$soup->id) }}">View Details</a>
            <p class="card-text">{{ $soup->benefits }}</p>
            <p class="card-text">{{ $soup->season }}</p>
        </div>
    </div>
</div>
