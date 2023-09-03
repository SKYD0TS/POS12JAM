@section('topbar')
    <div class="topbar">
        <div>
            <h5>Furnitoko</h5>
        </div>
        <div class="btn-group">
            <li><button class="dropdown-toggle bg-transparent border-0"
                    data-bs-toggle="dropdown">{{ auth()->user()->person->username ?? 'no acc/dev' }}</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                </ul>
            </li>
        </div>
    </div>
@endsection
