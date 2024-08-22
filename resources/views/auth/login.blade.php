<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.Login_Form') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .btn-google {
            background-color: #ff5722; /* Orange background */
            color: white; /* White text */
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
            margin-top: 10px;
        }
        .btn-google:hover {
            background-color: #e64a19; /* Darker orange on hover */
        }
        .alert {
            color: red;
            margin-bottom: 15px;
        }
        .super {
            margin-top: 50px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; /* Đảm bảo padding không ảnh hưởng đến width */
        }

        .password-container {
            position: relative;
            width: 100%; /* Đảm bảo password container có cùng kích thước với input text */
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }
    </style>
</head>
<body>
<div class="container super">
    <h1>{{ __('messages.Login') }}</h1>
    @if(session('error'))
    <div class="alert">
        {{ session('error') }}
    </div>
    @endif
    <form method="POST" action="{{ route('login_user') }}">
        @csrf
        <input type="text" placeholder="{{ __('messages.Email') }}" required name="email" id="username" />
        <div class="password-container">
            <input type="password" placeholder="{{ __('messages.Password') }}" required name="password" id="password" />
            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
        </div>
        <input type="submit" value="{{ __('messages.Log_in') }}" />
        <a href="{{ route('password.request') }}">{{ __('messages.Lost_your_password') }}</a>
        <a href="{{ route('register') }}">{{ __('messages.Register') }}</a>
    </form>
    <div class="social-login">
        <a href="{{ url('/google') }}" class="btn btn-google">
            <i class="fab fa-google"></i> {{ __('messages.Login_with_Google') }}
        </a>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        var passwordField = document.getElementById('password');
        var passwordFieldType = passwordField.getAttribute('type');
        var togglePasswordIcon = document.getElementById('togglePassword');

        if (passwordFieldType === 'password') {
            passwordField.setAttribute('type', 'text');
            togglePasswordIcon.classList.remove('fa-eye');
            togglePasswordIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.setAttribute('type', 'password');
            togglePasswordIcon.classList.remove('fa-eye-slash');
            togglePasswordIcon.classList.add('fa-eye');
        }
    });
</script>
</body>
</html>
