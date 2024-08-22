<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\password_reset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (!Auth::check()) {
            $user = User::where('email', $request->input('email'))->first();

            if ($user && Hash::check($request->input('password'), $user->password)) {
                // Đăng nhập thành công
                Auth::login($user);

                // Chuyển hướng tùy thuộc vào 'Role'
                return redirect()->route($user->Role == 1 ? 'home' : 'index');
            }
        }

        // Đăng nhập không thành công hoặc đã đăng nhập trước đó
        return redirect()->route('login')->with('error', 'Đăng nhập không thành công');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(StoreUserRequest $request)
    {
        if (!Auth::check()) {
            // Tạo người dùng mới từ dữ liệu request đã được xác thực
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->created_at = now();
            $user->Role = 2;
            $user->image = 'user.jpg';
            $user->birthday = null;
            $user->phone = null;
            $user->gender = 0;

            $user->save();
        }

        return redirect()->route('login')->with('success', 'Đăng ký thành công. Vui lòng đăng nhập.');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('index');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(64);
            $password_reset = new password_reset();
            $password_reset->email = $request->email;
            $password_reset->token = $token;
            $password_reset->created_at = Carbon::now();
            $password_reset->save();

            Mail::send('auth.reset_passsword_form', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject("reset password");
            });

            return back()->with('success', 'Chúng tôi đã gửi một email để đặt lại mật khẩu của bạn.');
        }

        return back()->withErrors(['email' => 'Không tìm thấy người dùng với địa chỉ email này']);
    }

    public function showResetPasswordForm($token)
    {
        $password_reset = password_reset::where('token', $token)->first();

        if ($password_reset) {
            return view('auth.reset-password', ['token' => $token]);
        }

        return back()->withErrors(['token' => 'Liên kết đặt lại mật khẩu không hợp lệ']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $password_reset = password_reset::where('token', $request->token)->first();

        if ($password_reset) {
            $user = User::where('email', $password_reset->email)->first();

            if ($user) {
                // Cập nhật mật khẩu
                $user->password = Hash::make($request->input('password'));
                $user->save();

                // Xóa thông tin đặt lại mật khẩu
                $password_reset->delete();

                return redirect()->route('login')->with('status', 'Mật khẩu đã được đặt lại thành công');
            }
        }

        return back()->withErrors(['token' => 'Liên kết đặt lại mật khẩu không hợp lệ']);
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Lấy thông tin người dùng từ Google
            $user = Socialite::driver('google')->user();    
            $finduser = User::where('email', $user->email)->first();
            if ($finduser) {
                // Nếu người dùng đã tồn tại, đăng nhập
                Auth::login($finduser);
                return redirect()->intended('/');
            } else {
                // Nếu người dùng chưa tồn tại, tạo tài khoản mới và đăng nhập
                $newUser = User::create([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'google_id' => $user['id'],
                    'password' => Hash::make('123456'),
                    'Role' => 2,
                    'created_at' => now(),
                    'image' => 'user.jpg',
                ]);
                Auth::login($newUser);
                return redirect()->intended('/');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect('login')->with('error', 'Đăng nhập thất bại, vui lòng thử lại.');
        }
        
    }
}
