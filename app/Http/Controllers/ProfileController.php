<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function showProfile()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thực hiện.');
        }
        $user = Auth::user();
        return view('user.profile.show', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'gender' => 'nullable|integer|in:0,1,2',
            'birthday' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->gender = $request->input('gender');
        $user->birthday = $request->input('birthday');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/profile/'), $imageName);
            $user->image = $imageName;
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Thông tin cá nhân đã được cập nhật.');
    }

    public function showChangePasswordForm()
    {
        return view('profile.password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Mật khẩu đã được thay đổi.');
    }
}

