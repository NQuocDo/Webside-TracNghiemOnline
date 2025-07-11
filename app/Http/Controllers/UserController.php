<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function hienThiTenSinhVien()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return view('layout.student_layout')->with('user', $user);
        }
        return redirect('/login');
    }
}
