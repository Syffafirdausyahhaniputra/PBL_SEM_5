<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Pengguna</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
        /* Background styling */
        body {
            background-image: url('{{ asset('img/login.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed; /* Mengunci overlay ke seluruh layar */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Warna overlay */
            z-index: -1; /* Letakkan di belakang semua elemen */
        }


        /* Styling untuk card login */
        .login-box {
            width: 400px;
            background-color: #0D47A1; /* Warna biru */
            padding: 20px;
            border-radius: 20px; /* Card melengkung */
            box-shadow: 0px 4px 30px rgba(0, 0, 0, 0.2); /* Bayangan */
            color: white; /* Warna teks putih */
        }

        .login-box .card-header {
            background: none;
            border-bottom: none;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        /* Styling untuk input fields */
        .form-control {
            background: #d9d9d9; /* Abu terang */
            border: none;
            color: black; /* Warna teks input */
        }

        .form-control::placeholder {
            color: #757575; /* Placeholder lebih terang */
        }

        .input-group-text {
            background: #d9d9d9;
            border: none;
            color: black;
        }

        /* Styling untuk tombol */
        .btn-primary {
            background-color: #EFB509; /* Warna kuning */
            border: none;
            font-weight: bold;
            border-radius: 10px; /* Tombol melengkung */
            color: black; /* Warna teks tombol */
        }

        .btn-primary:hover {
            background-color: #f6cc50; /* Warna kuning lebih terang */
            color: black;
        }

        /* Rounded input fields */
        .input-group .form-control,
        .input-group .input-group-text {
            border-radius: 10px; /* Membuat input lebih melengkung */
        }
    </style>


</head>

<body>
    <div class="login-box">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>Login</b></a>
            </div>
            <div class="card-body">
                <form action="{{ url('login') }}" method="POST" id="form-login">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="username" name="username" class="form-control" 
                               placeholder="Username atau Email" value="{{ old('username') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <small id="error-username" class="error-text text-danger"></small>
                    </div>                    
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <small id="error-password" class="error-text text-danger"></small>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#form-login").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 4,
                        maxlength: 100
                    },
                    password: {
                        required: true,
                        minlength: 5,
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(function () {
                                    window.location = response.redirect;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>

</html>
