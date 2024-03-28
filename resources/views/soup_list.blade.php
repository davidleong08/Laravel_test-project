@foreach($soups as $soup)
    <div class="col-md-4 soup-item" data-season="{{ strtolower($soup->seasons->pluck('name')->join(', ')) }}">
        <div class="card mb-4">
            <img src="{{ $soup->image_url }}" alt="{{ $soup->name }}" class="card-img-top img-fluid">
            <div class="card-body">
                <h5 class="card-title">{{ $soup->name }}</h5>
                <p class="card-text">{{ $soup->description }}</p>
            </div>
        </div>
    </div>
@endforeach