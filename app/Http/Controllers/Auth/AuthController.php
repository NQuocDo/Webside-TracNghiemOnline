<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    //

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => [
                    'required',      
                    'email',          
                    'max:255',       
                ],
                'password' => 'required|min:6',
            ],
            [
                'email.email' => 'Email không đúng định dạng. Vui lòng nhập địa chỉ email hợp lệ.',
                'email.required' => 'Vui lòng nhập địa chỉ email.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            ]
        );

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            switch ($user->vai_tro) {
                case 'sinh_vien':
                    return redirect()->intended('/student/dashboard');
                case 'giang_vien':
                    return redirect()->intended('/lecturer/dashboard');
                case 'truong_khoa':
                    return redirect()->intended('/dean/dashboard');
                default:
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect('/login')->withErrors(['email' => 'Tài khoản không hợp lệ hoặc vai trò không xác định.']);
            }
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput();
    }

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate(); // Hủy bỏ session hiện tại
        $request->session()->regenerateToken(); // Tạo lại CSRF token
        return redirect('');
    }
}