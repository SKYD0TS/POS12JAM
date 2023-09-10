@section('sidebar')
    <div class="sidebar rounded-3">
        <ul>
            <li><a href="/dashboard"><i class="bi bi-app"></i></a></li>
            <div class="btn-group dropend">
                <li><button class="dropdown-toggle bg-transparent border-0" data-bs-toggle="dropdown"><i
                            class="bi bi-basket3"></i></button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/transactions/in">Transaction In</a></li>
                        <li><a class="dropdown-item" href="/transactions/out">Transaction Out</a></li>
                    </ul>
                </li>
            </div>
            <div class="btn-group dropend">
                <li><button class="dropdown-toggle bg-transparent border-0" data-bs-toggle="dropdown"><i
                            class="bi bi-pen"></i></button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/dashboard/products">Products</a></li>
                        <li><a class="dropdown-item" href="/dashboard/suppliers">Suppliers</a></li>
                        <li><a class="dropdown-item" href="/dashboard/categories">Categories</a></li>
                        <li><a class="dropdown-item" href="/dashboard/units">Units</a></li>
                    </ul>
                </li>
            </div>
            <li><a href="/admin/users"><i class="bi bi-people"></i></a></li>
        </ul>
    </div>
@endsection
