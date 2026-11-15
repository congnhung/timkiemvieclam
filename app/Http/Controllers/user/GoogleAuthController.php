<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->user();

            $user = User::where('email', $google_user->getEmail())->first();

            if (!$user) {
                $new_user = User::create([
                    'fullname' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'role' => 'user',
                ]);

                Auth::login($new_user);
                session()->flash('toastr', ['success' => 'Chào mừng bạn đến với website tìm kiếm việc làm']);
                return redirect()->intended('account/profile');
                
            }
            else {
                if ($user->role === 'employer' && (int)$user->status === 0) {
                    session()->flash('warning', 'Tài khoản Nhà tuyển dụng của bạn đang chờ Quản trị viên xem xét và phê duyệt trước khi có thể đăng nhập.');
                    session()->flash('toastr', ['warning' => 'Tài khoản đang chờ duyệt!']);
                    return redirect()->route('account.login');
                }
                if ((int)$user->status === 2) {
                    session()->flash('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Quản trị viên.');
                    session()->flash('toastr', ['error' => 'Tài khoản đã bị khóa!']);
                    return redirect()->route('account.login');
                }

                Auth::login($user);
                session()->flash('toastr', ['success' => 'Chào mừng bạn đến với website tìm kiếm việc làm']);
                return redirect()->intended('account/profile');
            }
        } catch (\Throwable $th) {
            dd('Something went wrong !'. $th->getMessage());
        }
    }
}
