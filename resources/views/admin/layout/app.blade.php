<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <nav class="navbar navbar-expand-lg navbar-light flex-column">
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('home') }}">Admin Dashboard</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('exams.index') }}">Quản Lý bộ câu hỏi</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('topic.index') }}">Quản Lý bài viết</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('comment.index') }}">Quản Lý Bình Luận</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('users.index') }}">Quản Lý Người dùng</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- Content -->
            <div class="col-md-10 content">
                <div class="row">
                    <!-- User Info and Logout Button -->
                    <div class="col-md-12 bg-light">
                        @auth
                        <div class="text-right py-2 pr-4">
                            Xin chào, {{ Auth::user()->name }}
                            <form action="{{ route('logout') }}" method="post" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary ml-2">Logout</button>
                            </form>
                        </div>
                        @endauth
                    </div>
                </div>
                <!-- Main Content -->
                <div class="row">
                    <div class="col-md-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>