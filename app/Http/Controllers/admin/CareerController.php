<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CareerController extends Controller
{
  public function index(Request $request)
  {
    $careers = Career::orderBy('created_at', 'DESC');
    if (!empty($request->keyword)) {
      $careers = $careers->where('name', 'like', '%' . $request->keyword . '%');
    }
    if (!empty($request->date)) {
      $careers = $careers->whereDate('created_at', $request->date);
    }
    $careers = $careers->paginate(5);
    return view('admin.career.list', compact('careers'));
  }
  public function postCreateCareer(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'name' => 'required|max:200|unique:careers,name',
        'status' => 'required'
      ],
      [
        'name.required' => 'Vui lòng nhập tên.',
        'name.max' => 'Tên không được vượt quá 200 ký tự.',
        'name.unique' => 'Tên này đã được sử dụng, vui lòng chọn tên khác.',
        'status.required' => 'Vui lòng chọn trạng thái.'
      ]
    );
    if ($validator->passes()) {
      $careers = new Career();
      $careers->name = $request->name;
      $careers->status = $request->status;
      $careers->isPopular = $request->isPopular ? $request->isPopular : '0';
      $careers->save();
      toastr()->success('Bạn đã thêm thành công.', ' ');
      return redirect()->back();
    } else {
      return redirect()->back()->withErrors($validator)->withInput();
    }
  }
  public function deleteCareer($id)
  {
    $career = Career::find($id);
    if ($career == null) {
      toastr()->error("Tên ngành nghề không tồn tại.", " ");
      return redirect()->back();
    }
    $career->delete();
    toastr()->success("Xóa thành công", " ");
    return redirect()->route('admin.career');
  }
  public function getEmployer()
  {
    return view('admin.employer.list');
  }
  public function getEditCareer($id)
  {
    if (!ctype_digit($id)) {
      return view("errors.404");
    }
    $careers = Career::orderBy('created_at', 'DESC')->paginate(5);
    $career = Career::findOrFail($id);
    return view('admin.career.edit', compact('careers', 'career'));
  }
  public function postEditCareer(Request $request, $id)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'name' => 'required|max:200|unique:careers,name,' . $id,
        'status' => 'required',
      ],
      [
        'name.required' => 'Vui lòng nhập tên.',
        'name.max' => 'Tên không được vượt quá 200 ký tự.',
        'name.unique' => 'Tên này đã được sử dụng, vui lòng chọn tên khác.',
        'status.required' => 'Vui lòng chọn trạng thái.'
      ]
    );
    if ($validator->passes()) {
      $career =  Career::find($id);
      $career->name = $request->name;
      $career->status = $request->status;
      $career->isPopular = $request->isPopular ? $request->isPopular : '0';
      $career->save();
      toastr()->success("Cập nhật thành công.", ' ');
      return redirect()->back();
    } else {
      return redirect()->back()->withErrors($validator)->withInput();
    }
  }
}
