<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Document</title>
    {{-- <link rel="stylesheet" href="{{ asset('Assets/datatables') }}/dataTables.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('Assets/datatables') }}/dataTables.bootstrap5.min.css">
    {{-- <link rel="stylesheet" href="{{ asset('Assets/datatables') }}/built-datatables.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('Assets/bootstrap') }}/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('Assets/toastr') }}/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('Assets/select2') }}/select2.min.css">
    <link rel="stylesheet" href="{{ asset('Assets/css') }}/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    @stack('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@200&display=swap');

        body {
            font-family: 'IBM Plex Sans',
                sans-serif;
        }

        .badge {
            font-family: 'Raleway', sans-serif;

        }

        .hidden-column {
            display: none;
        }

        .paginate_button,
        .paginate_button:hover {
            padding: 0px;
            background-color: unset;
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
    <script src="{{ asset('Assets/datatables') }}/jquery.dataTables.min.js"></script>
    {{-- <script src="{{ asset('Assets/datatables') }}/dataTables.min.js"></script> --}}
    {{-- <script src="{{ asset('Assets/datatables') }}/built-datatables.min.js"></script> --}}
    <script src="{{ asset('Assets/datatables') }}/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('Assets/datatables') }}/dataTables.responsive.min.js"></script>
    <script src="{{ asset('Assets/toastr') }}/toastr.min.js"></script>
    <script src="{{ asset('Assets/bootstrap') }}/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('Assets/select2') }}/select2.min.js"></script>
    <script>
        $('.select-searchable').select2({
            dropdownParent: $('#model-modal')
        });
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
    @stack('scripts')

</body>

</html>
