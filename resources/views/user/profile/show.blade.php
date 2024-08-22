@extends('user.layout.app')

@section('content')
<div class="container">
    <div id="profile-container">
        <div class="text-center my-4">
            <button class="btn btn-outline-primary" id="change-password-link">Thay đổi mật khẩu</button>
            <button class="btn btn-outline-secondary" id="profile-info-link">Quay lại hồ sơ cá nhân</button>
        </div>

        <div id="profile-info">
            <h1>Hồ sơ cá nhân</h1>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Tên</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="gender">Giới tính</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="" {{ is_null($user->gender) ? 'selected' : '' }}>Chọn</option>
                            <option value="0" {{ $user->gender == 0 ? 'selected' : '' }}>Nam</option>
                            <option value="1" {{ $user->gender == 1 ? 'selected' : '' }}>Nữ</option>
                            <option value="2" {{ $user->gender == 2 ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>

                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="birthday">Ngày sinh</label>
                        <input type="date" class="form-control" id="birthday" name="birthday" value="{{ old('birthday', $user->birthday) }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="image">Ảnh đại diện</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                        @if ($user->image)
                            <img src="{{ asset('img/profile/' . $user->image) }}" alt="Ảnh đại diện" class="img-thumbnail" width="150">
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>

        <div id="change-password" style="display: none;">
            <h1>Thay đổi mật khẩu</h1>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf

                <div class="form-group">
                    <label for="current_password">Mật khẩu hiện tại</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                    @error('current_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                    @error('new_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('change-password-link').addEventListener('click', function() {
        document.getElementById('profile-info').style.display = 'none';
        document.getElementById('change-password').style.display = 'block';
    });

    document.getElementById('profile-info-link').addEventListener('click', function() {
        document.getElementById('profile-info').style.display = 'block';
        document.getElementById('change-password').style.display = 'none';
    });
</script>
@endsection
