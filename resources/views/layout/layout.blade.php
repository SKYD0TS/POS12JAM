<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('Assets/datatables') }}/datatables.min.css">
    <link rel="stylesheet" href="{{ asset('Assets/bootstrap') }}/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('Assets/toastr') }}/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('Assets/css') }}/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    @stack('styles')
    <style>
        .hidden-column {
            display: none;
        }
    </style>
</head>

<body>
    @yield('topbar')
    @yield('sidebar')
    <main>
        <div class="container-fluid">
            @yield('main')
        </div>
    </main>

    <script src="{{ asset('Assets/jquery') }}/jquery-3.7.0.js"></script>
    <script src="{{ asset('Assets/datatables') }}/datatables.min.js"></script>
    <script src="{{ asset('Assets/toastr') }}/toastr.min.js"></script>
    <script src="{{ asset('Assets/bootstrap') }}/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "2000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>
</body>

</html>
