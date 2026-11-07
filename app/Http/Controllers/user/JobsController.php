<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Mail\JobNotificationEmail;
use Illuminate\Http\Request;
use App\Models\Careers;
use App\Models\JobType;
use App\Models\Job;
use App\Models\SavedJob;
use App\Models\User;
use App\Models\NotificationUser;
use App\Models\NotificationEmployer;
use App\Models\JobApplication;
use App\Models\Resume;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{

  public function index(Request $request)
  {
    $provincesList = config('vietnam_provinces_34.provinces', []);
    $careers = Careers::where('status', 1)->orderBy('name', 'ASC')->get();
    $jobTypes = JobType::where('status', 1)->orderBy('name', 'ASC')->get();

    $jobs = Job::where('status', 1);

    // Tìm kiếm theo từ khóa (Tiêu đề, Kỹ năng/Keywords, Tên công ty, Mô tả)
    if (!empty($request->keyword)) {
      $keyword = trim($request->keyword);
      $jobs = $jobs->where(function ($query) use ($keyword) {
        $query->where('title', 'like', '%' . $keyword . '%')
          ->orWhere('keywords', 'like', '%' . $keyword . '%')
          ->orWhere('company_name', 'like', '%' . $keyword . '%')
          ->orWhere('description', 'like', '%' . $keyword . '%');
      });
    }

    // Lọc theo Tỉnh / Thành phố (Hỗ trợ 34 tỉnh thành mới & tự động map các tỉnh cũ đã sáp nhập)
    if (!empty($request->province)) {
      $provinceInput = trim($request->province);

      $searchKeywords = [$provinceInput];
      foreach ($provincesList as $p) {
        if (
          $p['name'] == $provinceInput ||
          $p['full_name'] == $provinceInput ||
          $p['id'] == $provinceInput ||
          str_contains($provinceInput, $p['name']) ||
          (!empty($p['merged_from']) && in_array($provinceInput, $p['merged_from']))
        ) {
          $searchKeywords[] = $p['name'];
          $searchKeywords[] = $p['full_name'];
          if (!empty($p['merged_from'])) {
            $searchKeywords = array_merge($searchKeywords, $p['merged_from']);
          }
          break;
        }
      }
      $searchKeywords = array_unique(array_filter($searchKeywords));

      $jobs = $jobs->where(function ($q) use ($searchKeywords) {
        foreach ($searchKeywords as $kw) {
          $q->orWhere('province', 'like', '%' . $kw . '%')
            ->orWhere('district', 'like', '%' . $kw . '%')
            ->orWhere('wards', 'like', '%' . $kw . '%')
            ->orWhere('location_detail', 'like', '%' . $kw . '%');
        }
      });
    }

    // Lọc theo Ngành nghề
    $career = $request->career ?? $request->category;
    if (!empty($career)) {
      $jobs = $jobs->where('career_id', $career);
    }

    // Lọc theo Hình thức làm việc (Job Type)
    $jobTypeInput = $request->jobType ?? $request->job_type;
    $jobTypeArray = [];
    if (!empty($jobTypeInput)) {
      $jobTypeArray = is_array($jobTypeInput) ? $jobTypeInput : explode(',', $jobTypeInput);
      $jobTypeArray = array_map('intval', array_filter($jobTypeArray));
      if (!empty($jobTypeArray)) {
        $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
      }
    }

    // Lọc theo Kinh nghiệm
    if (!empty($request->experience)) {
      $exp = $request->experience;
      if ($exp == 'none' || $exp == '0') {
        $jobs = $jobs->where(function ($q) {
          $q->whereNull('experience')
            ->orWhere('experience', '0')
            ->orWhere('experience', 'none')
            ->orWhere('experience', 'like', '%không%')
            ->orWhere('experience', 'like', '%chưa%');
        });
      } elseif ($exp == 'under_1') {
        $jobs = $jobs->where(function ($q) {
          $q->where('experience', 'under_1')
            ->orWhere('experience', '<', 1)
            ->orWhere('experience', 'like', '%dưới 1%')
            ->orWhere('experience', 'like', '%< 1%');
        });
      } elseif ($exp == '5_plus' || $exp == '10_plus') {
        $jobs = $jobs->where(function ($q) {
          $q->where('experience', '>=', 5)
            ->orWhere('experience', '5_plus')
            ->orWhere('experience', '10_plus')
            ->orWhere('experience', 'like', '%trên 5%')
            ->orWhere('experience', 'like', '%trên 10%');
        });
      } else {
        $jobs = $jobs->where(function ($q) use ($exp) {
          $q->where('experience', $exp)
            ->orWhere('experience', 'like', '%' . $exp . ' năm%');
        });
      }
    }

    // Lọc theo Mức lương chuẩn xác
    if (!empty($request->salary_range)) {
      $allJobSalaries = Job::where('status', 1)->pluck('salary', 'id');
      $matchedSalaryIds = [];
      foreach ($allJobSalaries as $jobId => $salaryText) {
        if ($this->matchSalaryRange($salaryText, $request->salary_range)) {
          $matchedSalaryIds[] = $jobId;
        }
      }
      $jobs = $jobs->whereIn('id', $matchedSalaryIds);
    }

    // Lọc theo Cấp bậc (Job Level)
    if (!empty($request->job_level)) {
      $lvl = trim($request->job_level);
      $jobs = $jobs->where(function ($q) use ($lvl) {
        $q->where('job_level', 'like', '%' . $lvl . '%');
        if (mb_stripos($lvl, 'thực tập') !== false) {
          $q->orWhere('job_level', 'like', '%intern%')
            ->orWhere('job_level', 'like', '%fresher%')
            ->orWhere('title', 'like', '%thực tập%')
            ->orWhere('title', 'like', '%intern%');
        } elseif (mb_stripos($lvl, 'nhân viên') !== false) {
          $q->orWhere('job_level', 'like', '%chuyên viên%')
            ->orWhere('job_level', 'like', '%staff%')
            ->orWhere('job_level', 'like', '%middle%')
            ->orWhere('job_level', 'like', '%senior%');
        } elseif (mb_stripos($lvl, 'trưởng nhóm') !== false) {
          $q->orWhere('job_level', 'like', '%leader%')
            ->orWhere('job_level', 'like', '%lead%')
            ->orWhere('job_level', 'like', '%trưởng nhóm%');
        } elseif (mb_stripos($lvl, 'trưởng phòng') !== false) {
          $q->orWhere('job_level', 'like', '%quản lý%')
            ->orWhere('job_level', 'like', '%manager%');
        } elseif (mb_stripos($lvl, 'giám đốc') !== false) {
          $q->orWhere('job_level', 'like', '%director%')
            ->orWhere('job_level', 'like', '%quản lý%');
        }
      });
    }

    $jobs = $jobs->with(['jobType', 'career', 'user']);

    // Sắp xếp
    if ($request->sort == '0' || $request->sort == 'oldest') {
      $jobs = $jobs->orderBy('created_at', 'ASC');
    } else {
      // Mặc định TopCV: Ưu tiên tin nổi bật (isFeatured) lên đầu, sau đó theo ngày đăng mới nhất
      $jobs = $jobs->orderBy('isFeatured', 'DESC')->orderBy('created_at', 'DESC');
    }

    $jobsCount = (clone $jobs)->count();
    $jobs = $jobs->paginate(10)->withQueryString();

    $savedJobIds = [];
    if (Auth::check()) {
      $savedJobIds = SavedJob::where('user_id', Auth::id())->pluck('job_id')->toArray();
    }

    return view('front.jobs', [
      'careers' => $careers,
      'jobTypes' => $jobTypes,
      'jobs' => $jobs,
      'jobsCount' => $jobsCount,
      'jobTypeArray' => $jobTypeArray,
      'savedJobIds' => $savedJobIds,
      'provinces' => $provincesList,
    ]);
  }

  public function detail($id)
  {
    $job = Job::where([
      'id' => $id,
    ])->with(['jobType', 'career', 'user'])->first();

    if ($job == null) {
      return view('errors.404');
    }

    $count = 0;
    if (Auth::check()) {
      $count = SavedJob::where([
        'user_id' => Auth::user()->id,
        'job_id' => $id,
      ])->count();
    }

    $userHasApplied = false;
    $userResumes = collect();
    if (Auth::check()) {
      $userHasApplied = JobApplication::where([
        'user_id' => Auth::user()->id,
        'job_id' => $id
      ])->count();

      $userResumes = Resume::where('user_id', Auth::id())
        ->orderBy('updated_at', 'DESC')
        ->get();
    }

    $applications = JobApplication::where('job_id', $id)->with('user')->get();

    // Việc làm tương tự cùng ngành nghề
    $relatedJobs = Job::where('career_id', $job->career_id)
      ->where('id', '!=', $job->id)
      ->where('status', 1)
      ->with(['jobType', 'user'])
      ->orderBy('isFeatured', 'DESC')
      ->orderBy('created_at', 'DESC')
      ->take(4)
      ->get();

    return view('front.jobDetail', [
      'job' => $job,
      'count' => $count,
      'applications' => $applications,
      'userHasApplied' => $userHasApplied,
      'userResumes' => $userResumes,
      'relatedJobs' => $relatedJobs,
    ]);
  }

  public function detail_employer($id)
  {
    $job = Job::where([
      'id' => $id,
    ])->with(['jobType', 'career', 'user'])->first();

    if ($job == null) {
      return view('errors.404');
    }

    $applicationCount = JobApplication::where('job_id', $id)->count();

    $isApplicationFull = $applicationCount == $job->vacancy;

    $applications = JobApplication::where('job_id', $id)->with(['user', 'resume'])->get();



    return view('front.jobDetail_employer', [
      'job' => $job,
      'applications' => $applications,
      'isApplicationFull' => $isApplicationFull,
    ]);
  }

  public function applyJob(Request $request)
  {
    $cvType = $request->input('cv_type', 'upload');

    if ($cvType === 'online') {
      $rules = [
        'resume_id' => 'required|exists:resumes,id',
      ];
      $messages = [
        'resume_id.required' => 'Vui lòng chọn một CV Online để nộp.',
        'resume_id.exists' => 'CV Online đã chọn không hợp lệ.',
      ];
    } else {
      $rules = [
        'cv' => 'required|file|mimes:pdf,doc,docx|max:10240',
      ];
      $messages = [
        'cv.required' => 'Bạn phải tải lên file CV.',
        'cv.file' => 'CV phải là một tệp tin hợp lệ.',
        'cv.mimes' => 'CV phải có định dạng .pdf, .doc hoặc .docx.',
        'cv.max' => 'Dung lượng file CV tối đa là 10MB.',
      ];
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->passes()) {
      $id = $request->id;
      $job = Job::where('id', $id)->first();

      if ($job == null) {
        return response()->json([
          'status' => false,
          'message' => 'Không tìm thấy công việc.',
        ]);
      }

      $employer_id = $job->user_id;

      if ($employer_id == Auth::user()->id) {
        return response()->json([
          'status' => false,
          'message' => 'Bạn không thể nộp đơn vào công việc của riêng bạn.',
        ]);
      }

      $jobApplicationCount = JobApplication::where([
        'user_id' => Auth::user()->id,
        'job_id' => $id
      ])->count();

      if ($jobApplicationCount > 0) {
        return response()->json([
          'status' => false,
          'message' => 'Bạn đã nộp đơn công việc này trước đó.',
        ]);
      }

      $filename = null;
      $resumeId = null;

      if ($cvType === 'online') {
        $resume = Resume::where('id', $request->resume_id)->where('user_id', Auth::id())->first();
        if (!$resume) {
          return response()->json([
            'status' => false,
            'message' => 'CV Online không tồn tại hoặc không thuộc sở hữu của bạn.',
          ]);
        }
        $resumeId = $resume->id;
      } else {
        if ($request->hasFile('cv')) {
          $file = $request->file('cv');
          $filename = Auth::user()->email . '_' . time() . '.' . $file->getClientOriginalExtension();
          $destinationPath = public_path('/assets/user/CV');
          $file->move($destinationPath, $filename);
        }
      }

      $application = new JobApplication();
      $application->job_id = $id;
      $application->user_id = Auth::user()->id;
      $application->employer_id = $employer_id;
      $application->applied_date = now();
      $application->cv_type = $cvType;
      $application->resume_id = $resumeId;
      $application->cv_path = $filename ?? ('online:' . $resumeId);
      $application->status = "0";
      $application->save();

      NotificationEmployer::create([
        'employer_id' => $employer_id,
        'job_notification_id' => $id,
        'user_id' => Auth::user()->id,
        'type' => 'applied',
      ]);

      try {
        $employer = User::where('id', $employer_id)->first();
        $mailData = [
          'employer' => $employer,
          'user' => Auth::user(),
          'job' => $job,
        ];
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));
      } catch (\Throwable $e) {
        // Mail error shouldn't fail application
      }

      return response()->json([
        'status' => true,
        'message' => 'Nộp hồ sơ thành công! Nhà tuyển dụng sẽ sớm liên hệ với bạn.',
      ]);
    } else {
      return response()->json([
        'status' => false,
        'errors' => $validator->errors()->first(),
      ]);
    }
  }

  public function saveJob(Request $request)
  {
    if (!Auth::check()) {
      $message = "Bạn cần đăng nhập để thực hiện chức năng này.";
      return response()->json([
        'status' => false,
        'message' => $message,
      ]);
    }

    $id = $request->id;

    $job = Job::find($id);

    if ($job == null) {
      $message = "Không tìm thấy công việc";
      return response()->json([
        'status' => false,
        'message' => $message,
      ]);
    }

    // Check if user already saved the job
    $savedJob = SavedJob::where([
      'user_id' => Auth::user()->id,
      'job_id' => $id,
    ])->first();

    if ($savedJob) {
      // Nếu đã lưu thì xóa công việc khỏi danh sách yêu thích
      $savedJob->delete();
      $message = "Đã hủy yêu thích";
      return response()->json([
        'status' => true,
        'action' => 'unsaved',
        'message' => $message,
      ]);
    } else {
      // Nếu chưa lưu thì thêm vào danh sách yêu thích
      $newSavedJob = new SavedJob;
      $newSavedJob->job_id = $id;
      $newSavedJob->user_id = Auth::user()->id;
      $newSavedJob->save();

      $message = "Đã thêm vào mục công việc yêu thích";
      return response()->json([
        'status' => true,
        'action' => 'saved',
        'message' => $message,
      ]);
    }
  }

  public function downloadCv($cvPath)
  {
    $file = public_path('assets/user/CV/' . $cvPath);

    if (!file_exists($file)) {
      return back()->with('toastr', ['error' => 'File CV không tồn tại hoặc đã bị xóa']);
    }

    return response()->download($file);
  }

  public function viewCv($cvPath)
  {
    $file = public_path('assets/user/CV/' . $cvPath);

    if (!file_exists($file)) {
      return back()->with('toastr', ['error' => 'File CV không tồn tại hoặc đã bị xóa']);
    }

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if ($extension === 'pdf') {
      return response()->file($file, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . basename($file) . '"'
      ]);
    }

    return response()->download($file);
  }

  public function processApplication(Request $request)
  {
    $application = JobApplication::with(['job', 'user'])->find($request->id);

    if ($application) {
      if ($request->has('approval_status')) {
        $status = (string) $request->approval_status;
        $application->status = $status;

        $type = ($status === '4') ? 'rejected' : 'approved';

        \DB::table('notifications_user')->insert([
          'user_id' => $application->user_id,
          'job_notification_id' => $application->id,
          'message' => $request->message ? $request->message : null,
          'type' => $type,
          'created_at' => now(),
          'updated_at' => now(),
        ]);
      }

      $application->save();

      return redirect(url()->previous())->with('toastr', ['success' => 'Cập nhật trạng thái hồ sơ ứng viên thành công!']);
    } else {
      return redirect()->back()->with('toastr', ['error' => 'Có lỗi xảy ra, hãy thử lại!']);
    }
  }

  public function destroy($id)
  {
    $notification = NotificationUser::find($id);
    if ($notification && $notification->user_id == auth()->id()) {
      $notification->delete();
      return redirect()->back();
    }
    return redirect()->back()->with('toastr', ['error' => 'Có lỗi xảy ra. Vui lòng thử lại.']);
  }

  public function delete_notification_Employer($id)
  {
    $notification = NotificationEmployer::find($id);
    if ($notification && $notification->employer_id  == auth()->id()) {
      $notification->delete();
      return redirect()->back();
    }
    return redirect()->back()->with('toastr', ['error' => 'Có lỗi xảy ra. Vui lòng thử lại.']);
  }

  /**
   * So khớp mức lương thực tế trong CSDL với khoảng lương tìm kiếm
   */
  private function matchSalaryRange($salaryStr, $range)
  {
    if (empty($salaryStr)) {
      return $range === 'negotiable';
    }

    $s = mb_strtolower(trim($salaryStr));
    $isNegotiable = (bool) preg_match('/(thương lượng|cạnh tranh|thỏa thuận|thoa thuan)/u', $s);

    if ($range === 'negotiable') {
      return $isNegotiable;
    }

    if ($isNegotiable) {
      return false;
    }

    $min = 0;
    $max = 0;

    // 1. Lương ngoại tệ USD ($)
    if (strpos($s, '$') !== false) {
      preg_match_all('/(\d+)/', $s, $matches);
      if (!empty($matches[0])) {
        $vals = array_map('intval', $matches[0]);
        // Quy đổi ước tính sang triệu VNĐ (vd: 1000$ ~ 25 triệu)
        $min = min($vals) * 0.025;
        $max = max($vals) * 0.025;
      }
    }
    // 2. Lương dạng số có dấu chấm (vd: 5.000.000 - 25.000.000 hoặc 20.000.000 VND)
    elseif (preg_match('/\d{1,3}\.\d{3}\.\d{3}/', $s)) {
      preg_match_all('/(\d{1,3})\.000\.000/', $s, $m);
      if (!empty($m[1])) {
        $vals = array_map('intval', $m[1]);
        $min = min($vals);
        $max = max($vals);
      }
    }
    // 3. Lương số nguyên thuần túy (vd: 6000000, 20000000)
    elseif (preg_match('/^\d{7,10}$/', $s)) {
      $val = intval($s) / 1000000;
      $min = $val;
      $max = $val;
    }
    // 4. Lương chữ hoặc số kèm triệu/tr (vd: 18 triệu - 20 triệu, 5-7 triệu, 15 - 30tr)
    else {
      preg_match_all('/(\d+)/', $s, $m);
      if (!empty($m[1])) {
        $vals = array_map('intval', $m[1]);
        $vals = array_filter($vals, function ($v) {
          return $v > 0 && $v < 1000;
        });
        if (!empty($vals)) {
          $min = min($vals);
          $max = max($vals);
        }
      }
    }

    if ($min == 0 && $max == 0) {
      return false;
    }

    switch ($range) {
      case 'under_10':
        return $min < 10;
      case '10_15':
        return ($min <= 15 && $max >= 10);
      case '15_20':
        return ($min <= 20 && $max >= 15);
      case '20_25':
        return ($min <= 25 && $max >= 20);
      case 'above_25':
        return $max >= 25;
      default:
        return true;
    }
  }
}