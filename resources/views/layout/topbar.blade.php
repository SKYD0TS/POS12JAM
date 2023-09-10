@section('topbar')
    <div class="topbar rounded-3">
        <div>
            <h5 class="m-0">Furnitoko</h5>
        </div>
        <div class="btn-group">
            <li>
                <span class="badge text-bg-info my-auto my-auto">{{ auth()->user()->role->name }}</span>
                <button class="dropdown-toggle bg-transparent border-0" data-bs-toggle="dropdown">
                    @if (auth()->user()->person->username)
                        <span>{{ auth()->user()->person->username }}</span>
                    @else
                        'no acc/dev'
                    @endif
                </button>
                <div class="dropdown-menu">
                    <div><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></div>
                </div>
            </li>
        </div>
    </div>
@endsection
