<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">FlatManager</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        @auth
            @if(auth()->user()->role === 'admin')
                <li class="nav-item"><a class="nav-link" href="{{ route('owners.index') }}">House Owners</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('tenants.index') }}">Tenants</a></li>
            @elseif(auth()->user()->role === 'house_owner')
                <li class="nav-item"><a class="nav-link" href="{{ route('buildings.index') }}">Buildings</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('flats.index') }}">Flats</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('bill-categories.index') }}">Bill Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('bills.index') }}">Bills</a></li>
            @endif
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">Logout</button>
                </form>
            </li>
        @else
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
