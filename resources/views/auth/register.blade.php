<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.register') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        #content {
            text-align: center;
        }
        h1 {
            margin-bottom: 1rem;
            color: #333;
        }
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-group input {
            width: 100%;
            padding: 0.75rem 2.5rem 0.75rem 2.5rem; /* Chỉnh padding để phù hợp với 2 icon */
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .input-group .fa {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #aaa;
        }
        .input-group .toggle-password {
            right: 1rem;
            left: auto;
            cursor: pointer;
        }
        .input-group .error {
            color: red;
            font-size: 0.875rem;
            position: absolute;
            top: 100%;
            left: 0;
            transform: translateY(0.25rem);
        }
        input[type="submit"] {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error, .success {
            display: block;
            margin-top: 1rem;
            font-size: 0.875rem;
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
<div class="container">
    <section id="content">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h1>{{ __('messages.register') }}</h1>
            <div class="input-group">
                <i class="fa fa-user"></i>
                <input type="text" placeholder="{{ __('messages.username') }}" required name="name" id="name" value="{{ old('name') }}" />
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="input-group">
                <i class="fa fa-envelope"></i>
                <input type="text" placeholder="{{ __('messages.email') }}" required name="email" id="email" value="{{ old('email') }}" />
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" placeholder="{{ __('messages.password') }}" required name="password" id="password" />
                <i class="fa fa-eye toggle-password"></i>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" placeholder="{{ __('messages.confirm_password') }}" required name="password_confirmation" id="password_confirmation" />
                <i class="fa fa-eye toggle-password"></i>
                @error('password_confirmation')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <input type="submit" value="{{ __('messages.register_button') }}" />
            </div>
            @if(session('error'))
                <span class="error">{{ session('error') }}</span>
            @endif
            @if(session('success'))
                <span class="success">{{ session('success') }}</span>
            @endif
        </form>
    </section>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(togglePassword => {
        togglePassword.addEventListener('click', function () {
            const passwordField = this.previousElementSibling;
            const passwordFieldType = passwordField.getAttribute('type');
            
            if (passwordFieldType === 'password') {
                passwordField.setAttribute('type', 'text');
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                passwordField.setAttribute('type', 'password');
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
</script>
</body>
</html>
