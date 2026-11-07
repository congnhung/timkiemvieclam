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
    $jobs = $jobs->with('career')->paginate(7);
    return view('admin.job.list', compact('jobs'));
  }
  public function editJob($id)
  {
    if (!ctype_digit($id)) {
      return view("errors.404");
    }
    $job = Job::with('jobType')->findOrFail($id);
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
        'salary' => 'required|integer',
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
        'salary.integer' => 'Mức lương phải là một con số hợp lệ.',
        'salary.required' => 'Mức lương là bắt buộc',
      ]
    );
    if ($validator->passes()) {
      $job = Job::find($id);
      $job->title = $request->title;
      $job->company_name = $request->company_name;
      $job->salary = $request->salary;
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
      $job->keywords = $request->keywords;
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
