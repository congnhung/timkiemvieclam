<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // Nếu user chưa đăng nhập và cố gắng truy cập tới trang profile sẽ chuyển về trang login
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('account.login');
    }
}
