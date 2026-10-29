<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Xác thực người dùng bằng Laravel Auth (guard "web", session).
 */
class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // Điều hướng theo vai trò
        if (Auth::user()->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Đăng nhập thành công với quyền Quản trị viên.');
        }

        return redirect()->intended(route('home'))
            ->with('success', 'Đăng nhập thành công! Chào mừng '.Auth::user()->name.'.');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.min' => 'Họ và tên phải có ít nhất :min ký tự.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // tự động hash nhờ cast "hashed"
            'role' => User::ROLE_USER,            // đăng ký mới luôn là User thường
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Đăng ký tài khoản thành công! Bạn có thể gửi yêu cầu mượn thiết bị ngay.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất khỏi hệ thống.');
    }
}
