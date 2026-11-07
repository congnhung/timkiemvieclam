<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

use function Laravel\Prompts\error;

class AccountController extends Controller
{
  public function profile()
  {
    $id = Auth::user()->id;
    $user = User::find($id);
    return view('admin.account.profile', compact('user'));
  }
  public function updateImageProfile(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'image' => 'required|image'
    ], [
      'image.required' => 'Vui lòng tải lên một hình ảnh.',
      'image.image' => 'Tệp bạn chọn phải là định dạng hình ảnh.'
    ]);

    if ($validator->passes()) {
      $id = Auth::user()->id;
      $old_image = public_path('admin/upload/profile/' . Auth::user()->image);
      $image = $request->file('image');
      $name_gen = $id . '-' . time() . '.' . $image->getClientOriginalExtension();
      $image->move(public_path('admin/upload/profile'), $name_gen);
      $sourcePath = public_path('admin/upload/profile/' . $name_gen);
      try {
        if (class_exists(ImageManager::class) && extension_loaded('gd')) {
          $manager = new ImageManager(new Driver());
          $img = $manager->read($sourcePath);
          $img->cover(150, 150);
          $img->save($sourcePath);
        }
      } catch (\Throwable $e) {
        Log::error('Error processing admin profile image: ' . $e->getMessage());
      }
      if (file_exists($old_image)) {
        unlink($old_image);
      }
      User::where('id', $id)->update([
        'image' => $name_gen,
        'updated_at' => Carbon::now()
      ]);
      toastr()->success("Cập nhật thành công.", ' ');
      return response()->json([
        'status' => true,
        'errors' => [],
      ]);
      // return redirect()->route('admin.profile');
    } else {
      return response()->json([
        'status' => false,
        'errors' => $validator->errors(),
      ]);
    }
  }
  public function updateProfile(Request $request)
  {
    $id = Auth::user()->id;
    $validator = Validator::make(
      $request->all(),
      [
        'fullname' => 'required|min:5|max:20',
        'email' => 'required|email|unique:users,email,' . $id . ',id',
      ],
      [
        'fullname.required' => 'Vui lòng nhập tên.',
        'fullname.min' => 'Tên phải có ít nhất 5 ký tự.',
        'fullname.max' => 'Tên không được dài quá 20 ký tự.',
        'email.required' => 'Vui lòng nhập địa chỉ email.',
        'email.email' => 'Địa chỉ email không hợp lệ.',
        'email.unique' => 'Email này đã được sử dụng.',
      ]
    );
    if ($validator->passes()) {
      $user = User::find($id);
      $user->fullname = $request->fullname;
      $user->email = $request->email;
      $user->save();
      toastr()->success("Cập nhật thành công.", ' ');
      return response()->json([
        'status' => true,
        'errors' => [],
      ]);
    } else {
      return response()->json([
        'status' => false,
        'errors' => $validator->errors(),
      ]);
    }
  }
  public function changePassword(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "old_password" => 'required',
        "new_password" => [
          'required',
          'min:8',
          'regex:/[A-Z]/',
          'regex:/[a-z]/',
          'regex:/[!@#$%^&*(),.?":{}|<>]/',
        ],
        "confirm_password" => 'required|same:new_password',
      ],
      [
        'old_password.required' => 'Vui lòng nhập mật khẩu cũ.',
        'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
        'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
        'new_password.regex' => 'Mật khẩu mới phải chứa ít nhất 1 chữ cái in hoa, 1 chữ cái thường và 1 ký tự đặc biệt.',
        'confirm_password.required' => 'Vui lòng xác nhận mật khẩu mới.',
        'confirm_password.same' => 'Mật khẩu không khớp với mật khẩu mới.',
      ]
    );
    if ($validator->fails()) {
      return response()->json([
        'status' => false,
        'errors' => $validator->errors(),
      ]);
    }
    if (Hash::check($request->old_password, Auth::user()->password) == false) {
      toastr()->error('Mật khẩu cũ không đúng', ' ');
      return response()->json([
        'status' => true,
      ]);
    }
    $user = User::find(Auth::user()->id);
    $user->password = Hash::make($request->new_password);
    $user->save();
    toastr()->success('Đổi mật khẩu thành công', ' ');
    return response()->json([
      'status' => true,
    ]);
  }
}
