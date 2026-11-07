<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobApplyController extends Controller
{
  public function index(Request $request)
  {
    $jobApply = JobApply::latest();
    if (!empty($request->keyword)) {
      // $user = User::where('fullname', 'like', '%' . $request->keyword . '%')->first();
      $user = DB::table('users')
        ->join('job_applications', 'users.id', '=', 'job_applications.user_id')
        ->where('users.fullname', 'LIKE', '%' . $request->keyword . '%')
        ->select('users.id')
        ->first();
      if ($user) {
        $jobApply = $jobApply->where('user_id', $user->id);
      } else {
        session()->flash('noti', 'Không tìm thấy dữ liệu với kết từ khóa ' . $request->keyword);
      }
    }
    if (!empty($request->date)) {
      $jobApply = $jobApply->whereDate('applied_date', $request->date);
    }
    $jobApply = $jobApply->with('user', 'job', 'employer')->paginate(5);
    return view('admin.applyJob.list', compact('jobApply'));
  }
  public function deleteApllyJob($id)
  {
    $jobApply = JobApply::find($id);
    if ($jobApply == null) {
      toastr()->error('Ứng viên không tồn tại', ' ');
      return redirect()->back();
    }
    $jobApply->delete();
    toastr()->success("Xóa thành công", " ");
    return redirect()->route('admin.apply.job');
  }
}
