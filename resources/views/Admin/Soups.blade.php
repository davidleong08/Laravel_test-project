{{-- resources/views/soups/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Soup List</h1>
    <button>Add</button>

    <table>
    @foreach($soups as $soup)
        <tr>
            <td>{{ $soup->id }}</td>
            <td>{{ $soup->name }}</td>
            <td>{{ $soup->description }}</td>
            <td>{{ $soup->suitable_constitution }}</td>
            <td>
                <a href="{{route('admin.soups.show',['soup'=>$soup->id])}}" class="btn btn-primary">Edit</a>
                <form action="{{ url('soups/destroy', $soup->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="Delete"/>
                </form>
            </td>
        </tr>
    @endforeach
    </table>

    {{-- 登入和註銷的代碼 --}}
    @if(Auth::check())
        <!-- 用戶已登入時顯示 -->
        <a href="{{ route('logout') }}"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
        Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @else
        <!-- 用戶未登入時顯示 -->
        <a href="{{ route('login') }}">Login</a>
        <!-- 如果需要註冊按鈕 -->
        <a href="{{ route('register') }}">Register</a>
    @endif
</div>
@endsection
