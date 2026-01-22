<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{{ asset('admin/assets/images/fav.png') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">

    <style>
        body {
            background: linear-gradient(135deg, #f4f7fb, #eef2f7);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .login-logo img {
            height: 40px;
        }

        .login-title {
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .login-subtitle {
            text-align: center;
            font-size: 14px;
            color: #7a7a7a;
            margin-bottom: 30px;
        }

        .input-wrapper {
            margin-bottom: 18px;
        }

        .input-wrapper label {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }

        .input-wrapper input {
            width: 100%;
            height: 48px;
            padding: 0 14px;
            border-radius: 8px;
            border: 1px solid #e1e5eb;
            font-size: 14px;
            color: #000;
        }

        .input-wrapper input:focus {
            border-color: #6aa84f;
            outline: none;
        }

        .login-btn {
            width: 100%;
            height: 48px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
        }

        .error-text {
            font-size: 13px;
            font-weight: 500;
            color: #e53935;
            margin-top: 4px;
        }

        .login-footer {
            text-align: center;
            font-size: 13px;
            color: #8a8a8a;
            margin-top: 20px;
        }

        /* Error state */
        .input-wrapper input.input-error {
            border-color: #e53935;
            background: #fff5f5;
        }

        /* Smooth transition */
        .input-wrapper input {
            transition: border-color 0.2s ease, background-color 0.2s ease;
        }
    </style>
</head>

<body>

<div class="login-card">

    <!-- Logo -->
    <div class="login-logo">
        {{-- <img src="{{ asset('assets/images/logo/logo.svg') }}" alt="logo"> --}}
    </div>

    <!-- Title -->
    <h2 class="login-title">Admin Login</h2>
    <p class="login-subtitle">Sign in to access the dashboard</p>

    <!-- Form -->
    <form id="adminLoginForm">

        <div class="input-wrapper">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Email">
            <div class="error-text error-email"></div>
        </div>

        <div class="input-wrapper">
            <label>Password</label>
            <input type="password" name="password" placeholder="Password">
            <div class="error-text error-password"></div>
        </div>

        <div class="error-text text-center m-4" id="loginError"></div>

        <button type="submit" class="rts-btn btn-primary login-btn mw-100">
            Login Account
        </button>

    </form>

    <div class="login-footer">
        {{-- © {{ date('Y') }} Admin Panel --}}
    </div>
</div>

<script src="{{ asset('admin/assets/js/plugins.js') }}"></script>

<script>
    $('#adminLoginForm').on('submit', function (e) {
        e.preventDefault();

        // Reset errors
        $('.error-email, .error-password').text('');
        $('#loginError').text('');
        $('input').removeClass('input-error');

        $.ajax({
            url: "{{ route('admin.login.submit') }}",
            type: "POST",
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res.status) {
                    window.location.href = res.redirect;
                }
            },
            error: function (xhr) {

                // Validation errors
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    let errors = xhr.responseJSON.errors;

                    if (errors.email) {
                        $('input[name="email"]').addClass('input-error');
                        $('.error-email').text(errors.email[0]);
                    }

                    if (errors.password) {
                        $('input[name="password"]').addClass('input-error');
                        $('.error-password').text(errors.password[0]);
                    }
                    return;
                }

                // Invalid credentials / server error
                if (xhr.responseJSON?.message) {
                    $('#loginError').text(xhr.responseJSON.message);
                } else {
                    $('#loginError').text('Something went wrong. Please try again.');
                }
            }
        });
    });

    /* Remove error highlight on typing */
    $('input').on('input', function () {
        $(this).removeClass('input-error');
    });
</script>


</body>
</html>
