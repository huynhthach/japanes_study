
<div class="card-body">
    <section class="login spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login__form">
                        <h3>{{ __('New Password') }}</h3>
                        <form method="POST" action='{{ route("password.update") }}'>
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="input__item">
                                <div class="password-wrapper">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="New Password" required>
                                    <span id="password-icon" toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password" onclick="togglePasswordVisibility('#password')"></span>
                                </div>
                            </div>

                            <div class="input__item">
                                <div class="password-wrapper">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password" required>
                                    <span id="password-confirm-icon" toggle="#password-confirm" class="fa fa-fw fa-eye field-icon toggle-password" onclick="togglePasswordVisibility('#password-confirm')"></span>
                                </div>
                            </div>

                            <button type="submit" class="site-btn">{{ __('Reset Password') }}</button>

                            {{-- Hiển thị thông báo lỗi dưới nút gửi --}}
                            @if (count($errors) > 0)
                                <div role="alert">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
