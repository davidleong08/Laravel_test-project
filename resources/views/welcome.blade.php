@auth
    <div class="card">
      <div class="card-header">{{ __('Dashboard') }}</div>  
      <div class="card-body">
        @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
        @endif
          
        {{ __('You are logged in!') }}
      </div>
    </div>
  @endauth

  @guest
    <a href="{{ route('soups') }}">查看湯菜單</a> 
  @endguest

</div>