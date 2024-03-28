@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Soup Edit</h1>

<form action="{{ route('soups.update', $soup->id) }}" method="POST">
    @csrf
    @method('PATCH')

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="{{ $soup->name }}" required>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required>{{ $soup->description }}</textarea>

    <button type="submit">Update Soup</button>
</form>


</div>



@endsection
