<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployerController extends Controller
{
  public function getEmployer(Request $request)
  {
    $employers = User::where('role', 'employer');
    if (!empty($request->keyword)) {
      $employers = $employers->where('fullname', 'like', '%' . $request->keyword . '%');
    }
    if (!empty($request->date)) {
      $employers = $employers->whereDate('created_at', $request->date);
    }
    $employers = $employers->latest()->paginate(5);
    return view('admin.employer.list', compact('employers'));
  }
  public function editEmployer($id)
  {
    if (!ctype_digit($id)) {
      return view("errors.404");
    }
    $employers = User::where('role', 'employer')->orderBy('updated_at', 'DESC')->paginate(5);
    $employer = User::where('role', 'employer')->findOrFail($id)->first();
    return view('admin.employer.edit', compact('employers', 'employer'));
  }
  public function updateEmployer(Request $request, $id)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'fullname' => 'required|min:5|max:200',
        'email' => 'required|email|unique:users,email, ' . $id,
        'mobile' => ['required', 'regex:/^([0-9]{10})$/', 'starts_with:0'],
        'status' => 'required',
      ],
      [
        'fullname.required' => 'Vui lòng nhập họ và tên.',
        'fullname.min' => 'Họ và tên cần có ít nhất 5 ký tự.',
        'fullname.max' => 'Họ và tên không được vượt quá 200 ký tự.',
        'email.required' => 'Vui lòng nhập địa chỉ email.',
        'email.unique' => 'Email đã tồn tại, vui lòng sử dụng email khác',
        'email.email' => 'Vui lòng nhập một địa chỉ email hợp lệ.',
        'mobile.required' => 'Vui lòng nhập số điện thoại của bạn.',
        'mobile.regex' => 'Số điện thoại không hợp lệ, vui lòng nhập đúng 10 chữ số.',
        'mobile.starts_with' => 'Số điện thoại phải bắt đầu bằng số 0.',
        'status.required' => 'Vui lòng chọn trạng thái.',
      ]
    );
    if ($validator->passes()) {
      $user = User::find($id);
      $user->fullname = $request->fullname;
      $user->email = $request->email;
      $user->mobile = $request->mobile;
      $user->status = $request->status;
      $user->save();
      toastr()->success("Cập nhật thành công.", ' ');
      return redirect()->back();
    } else {
      return redirect()->back()->withErrors($validator)->withInput();
    }
  }
  public function deleteEmployer($id)
  {
    $employer = User::find($id);
    if ($employer == null) {
      toastr()->error("Tên người dùng không tồn tại", " ");
      return redirect()->back();
    }
    $employer->delete();
    toastr()->success("Xóa thành công", " ");
    return redirect()->route('admin.employer');
  }
}
