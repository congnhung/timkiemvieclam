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
                Auth::login($user);
                session()->flash('toastr', ['success' => 'Chào mừng bạn đến với website tìm kiếm việc làm']);
                return redirect()->intended('account/profile');
                
            }
        } catch (\Throwable $th) {
            dd('Something went wrong !'. $th->getMessage());
        }
    }
}
