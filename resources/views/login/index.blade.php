<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Document</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans&display=swap');
    </style>

    <link rel="stylesheet" href="{{ asset('Assets/bootstrap') }}/bootstrap.min.css">
    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            height: 100vh;
            width: 100vw;
            margin: 0px;
            background: linear-gradient(0deg, rgba(9, 78, 129, 1) 0%, rgba(235, 255, 200, 1) 100%);
        }

        .login-card {
            background-color: transparent;
            position: relative;
            width: 30%;

            display: flex;
            flex-direction: column;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%)
        }

        .login-card-header {
            background-color: transparent;
            justify-self: center;
            margin: 0px auto;
            width: 75%;
            background: rgba(90, 90, 90, 0.104);
            backdrop-filter: blur(2px);


            transform: translateY(50%)
        }

        .login-card-body {
            background-color: white;
            padding: 1.6rem;
            padding-top: 3.1rem;
            height: 70%;
        }

        .login-card-footer {
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <form id="login-form" method="POST" action='#' onsubmit="return false;">
            <div class="login-card-header rounded-4 shadow-sm">
                <h1 class="modal-title text-center"><span class="d-inline">Login</span></h1>
            </div>
            <div class="login-card-body rounded-4 shadow-lg">
                <div id="loginAlertDiv"></div>
                <div class="form-floating mb-4">
                    <input type="email" class="form-control" name="email" id="email"
                        placeholder="name@example.com">
                    <label for="email">Email</label>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                    <label for="password">Password</label>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="login-card-footer">
                    <button type="submit" id="login-button" class="btn btn-primary w-50">Login</button>
                </div>
                <a class="mt-3 d-block text-end" data-bs-toggle="collapse" href="#lupa-password">
                    Lupa password?
                </a>
                <div class="collapse text-end fst-italic" id="lupa-password">Kontak admin</div>
            </div>
        </form>
    </div>
    <script src="{{ asset('Assets/jquery') }}/jquery-3.7.0.js"></script>
    <script src="{{ asset('Assets/bootstrap') }}/bootstrap.bundle.min.js"></script>
    <script>
        $('#login-form :input').keydown(function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('');
        });
    </script>
    <script>
        $('#login-button').on('click', function(e) {
            $(e.target).addClass('disabled')

            const formData = new FormData(document.getElementById('login-form'));
            const options = {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: formData
            }

            fetch('{{ route('login-check') }}', options).then(response => response.json())
                .then(r => {
                    if (typeof r.errors !== 'undefined') {
                        $.each(r.errors, function(key, value) {
                            let errorInput = $('[name=' + key + ']');
                            errorInput.addClass('is-invalid');
                            errorInput.siblings('.invalid-feedback').text(value);
                        })
                    } else if (typeof r.LoginError !== 'undefined') {
                        appendAlert(r.LoginError, 'danger');
                    } else if (typeof r.intendedUrl !== 'undefined') {
                        window.location.href = r.intendedUrl;
                    }
                    $(e.target).removeClass('disabled');
                })
                .catch(error => {
                    $(e.target).removeClass('disabled');
                    console.error(error);
                });
        })

        const loginAlert = $('#loginAlertDiv')
        const appendAlert = (message, type) => {
            loginAlert.html('')
            const wrapper = document.createElement('div')
            wrapper.innerHTML = [
                `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                `   <div>${message}</div>`,
                '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                '</div>'
            ].join('')

            loginAlert.append(wrapper)
        }
    </script>

</body>


</html>
