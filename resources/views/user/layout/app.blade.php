<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS, Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/maincss.css') }}">
    <style>
        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight: bold;">
        <div class="container">
            <a class="navbar-brand" href="{{ route('index') }}">
                <img src="{{ asset('/img/img_main/image-removebg.png') }}" alt="" style="width: 200px;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('index') }}">{{ __('messages.Home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.about_us') }}">{{ __('messages.About Us') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.index') }}">{{ __('messages.Lessons') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        @guest
                        <a href="{{ route('login') }}" class="btn btn-login d-block">{{ __('messages.Login') }}</a>
                        @else
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if(Auth::user()->image)
                            <img src="{{  asset('img/profile/' . Auth::user()->image)    }}" alt="avatar" class="avatar">
                            @else
                            {{ Auth::user()->name }}
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownUser">
                            <a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ __('messages.Logout') }}</button>
                            </form>
                        </div>
                        @endguest
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('changeLanguage', 'en') }}">
                            <img src="{{ asset('/img/flags/us-flag.jpg') }}" alt="English" style="width: 30px; height: auto;">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('changeLanguage', 'vi') }}">
                            <img src="{{ asset('/img/flags/vn-flag.jpg') }}" alt="Vietnamese" style="width: 30px; height: auto;">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="container mt-5 pt-5">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">{{ __('messages.All rights reserved.') }}</p>
            <div class="mt-2">
                <a href="#" class="text-white mr-3"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white mr-3"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            var navbar = $('.navbar');
            $(window).scroll(function() {
                if ($(window).scrollTop() > 0) {
                    navbar.addClass('sticky-navbar');
                } else {
                    navbar.removeClass('sticky-navbar');
                }
            });
        });
    </script>
</body>

</html>
