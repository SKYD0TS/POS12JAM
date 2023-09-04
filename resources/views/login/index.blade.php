<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('Assets/bootstrap') }}/bootstrap.min.css">
    <style>
        body {
            background-color: aliceblue;
            height: 100vh;
            width: 100vw;
            margin: 0px;
        }

        .login-card {
            display: flex;
            flex-direction: column;
            position: relative;

            width: 30%;
            height: fit-content;
            min-height: 60%;
            border: 1px solid black;
            background-color: white;
            padding: 1.75rem;

            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);

            justify-content: space-between;
        }

        .login-card-header {
            background-color: whitesmoke;
            border-radius: inherit;
        }

        .login-card-body {
            height: 70%;
        }

        .login-card-footer {
            display: flex;
            bottom: 0px;
            justify-content: center;
        }

        .login-card-dialog {
            height: 100%;
        }
    </style>
</head>

<body>

    <form id="login-form" class="login-card rounded-1" method="POST" action='#' onsubmit="return false;">
        <div>
            <div class="login-card-header mb-4">
                <h1 class="modal-title rounded-1 text-center">Login</h1>
            </div>
            <div class="login-card-body border-top">
                <div class="mt-4" id="loginAlertDiv"></div>
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
            </div>
        </div>

        <div class="login-card-footer border-top">
            <button type="submit" id="login-button" class="btn btn-primary mt-4 w-50">Login</button>
        </div>
    </form>
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
            $.ajax({
                url: '{{ route('login') }}',
                type: 'post',
                data: $('#login-form').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(r) {
                    if (typeof r.errors !== 'undefined') {
                        $.each(r.errors, function(key, value) {
                            let errorInput = $('[name=' + key + ']');
                            errorInput.addClass('is-invalid');
                            errorInput.siblings('.invalid-feedback').text(value);
                        })
                    } else if (typeof r.LoginError !== 'undefined') {
                        appendAlert(r.LoginError, 'danger')
                    } else if (typeof r.LoginSuccess !== 'undefined') {
                        window.location.href = r.LoginSuccess
                    }
                    $(e.target).removeClass('disabled')

                },
                error: function(r) {
                    $(e.target).removeClass('disabled')
                    console.log(r)
                }
            })
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
