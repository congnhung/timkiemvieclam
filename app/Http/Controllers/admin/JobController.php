<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class JobController extends Controller
{
  public function index(Request $request)
  {
    $jobs = Job::orderBy('created_at', 'DESC');
    if (!empty($request->keyword)) {
      $jobs = $jobs->where('title', 'like', '%' . $request->keyword . '%');
    }
    if (!empty($request->date)) {
      $jobs = $jobs->whereDate('created_at', $request->date);
    }
    if ($request->has('status') && $request->status !== '' && $request->status !== 'all') {
      $jobs = $jobs->where('status', $request->status);
    }
    $jobs = $jobs->with('career')->paginate(7)->withQueryString();

    $pendingCount = Job::where('status', env('STATUS_PENDING', 0))->count();
    $approvedCount = Job::where('status', env('STATUS_APPROVED', 1))->count();
    $lockedCount = Job::where('status', env('STATUS_LOCKED', 2))->count();
    $allCount = Job::count();

    return view('admin.job.list', compact('jobs', 'pendingCount', 'approvedCount', 'lockedCount', 'allCount'));
  }
  public function editJob($id)
  {
    if (!ctype_digit($id)) {
      return view("errors.404");
    }
    $job = Job::with('jobType')->findOrFail($id);

    // Tự động đánh dấu đã đọc thông báo của công việc này khi admin mở xem
    if (auth()->check()) {
      try {
        auth()->user()->unreadNotifications
          ->filter(fn($n) => isset($n->data['job_id']) && $n->data['job_id'] == $id)
          ->markAsRead();
      } catch (\Throwable $e) {
        // bỏ qua nếu lỗi
      }
    }

    $jobTypes = JobType::orderBy('name', 'ASC')->get();
    $careers = Career::orderBy('name', 'ASC')->get();
    $provinces = config('vietnam_provinces_34.provinces', []);
    return view('admin.job.edit', compact('job', 'jobTypes', 'careers', 'provinces'));
  }
  public function updateJob(Request $request, $id)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'title' => 'required|min:5,max:200',
        'company_name' => 'required',
        'location_detail' => 'required',
        'career' => 'required',
        'experience' => 'required',
        'job_type' => 'required',
        'description' => 'required',
        'responsibility' => 'required',
        'qualifications' => 'required',
        'benefits' => 'required',
        'salary' => 'nullable|string|max:255',
      ],
      [
        'title.required' => 'Tiêu đề công việc là bắt buộc.',
        'title.min' => 'Tiêu đề phải có ít nhất 5 ký tự.',
        'title.max' => 'Tiêu đề không được vượt quá 200 ký tự.',
        'company_name.required' => 'Tên công ty là bắt buộc.',
        'location_detail.required' => 'Vui lòng nhập địa chỉ.',
        'career.required' => 'Vui lòng chọn ngành nghề.',
        'experience.required' => 'Vui lòng chọn kinh nghiệm làm việc.',
        'job_type.required' => 'Vui lòng chọn loại hình công việc.',
        'description.required' => 'Mô tả công việc là bắt buộc.',
        'responsibility.required' => 'Trách nhiệm công việc là bắt buộc.',
        'qualifications.required' => 'Kỹ năng & chuyên môn là bắt buộc.',
        'benefits.required' => ' Phúc lợi là bắt buộc.',
      ]
    );
    if ($validator->passes()) {
      $job = Job::find($id);
      $job->title = $request->title;
      $job->company_name = $request->company_name;
      $job->salary = !empty($request->salary) ? trim($request->salary) : 'Thỏa thuận';
      $job->career_id = $request->career;
      $job->experience = $request->experience;
      $job->status = $request->status;
      $job->job_type_id = $request->job_type;
      $job->isFeatured = $request->isFeatured;
      $job->company_website = $request->company_website;
      $job->description = $request->description;
      $job->responsibility = $request->responsibility;
      $job->qualifications = $request->qualifications;
      $job->benefits = $request->benefits;
      
      $keywords = $request->keywords;
      if (!empty($keywords)) {
        if (is_string($keywords) && str_starts_with(trim($keywords), '[') && str_ends_with(trim($keywords), ']')) {
          $decoded = json_decode($keywords, true);
          if (is_array($decoded)) {
            $keywords = implode(', ', array_map('trim', $decoded));
          }
        }
        $keywords = trim($keywords, " \t\n\r\0\x0B[]\"'");
        $parts = array_filter(array_map('trim', explode(',', $keywords)));
        $job->keywords = implode(', ', $parts);
      } else {
        $job->keywords = '';
      }
      $job->province = $request->province_name ? $request->province_name : $job->province;
      $job->district = $request->district_name ? $request->district_name : $job->district;
      $job->wards = $request->ward_name ? $request->ward_name : $job->wards;
      $job->location_detail = $request->location_detail;
      $job->company_website = $request->company_website;
      $job->save();
      toastr()->success('Cập nhật thành công.', ' ');
      return redirect()->route('admin.job');
    } else {
      return redirect()->back()->withErrors($validator)->withInput();
    }
  }
}
