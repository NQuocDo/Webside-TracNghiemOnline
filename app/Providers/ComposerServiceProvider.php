<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\SinhVien;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        View::composer('layout.lecturer_layout', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $view->with('user', $user);
            }
        });
        View::composer('layout.dean', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $view->with('user', $user);
            }
        });
        View::composer('layout.student_layout', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $view->with('user', $user);
            }
        });
    }
}
