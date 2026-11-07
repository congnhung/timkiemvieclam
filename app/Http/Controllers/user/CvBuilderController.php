<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CvBuilderController extends Controller
{
    /**
     * Màn hình tạo và chỉnh sửa CV Online
     */
    public function index(Request $request)
    {
        $resume = null;
        if ($request->has('id')) {
            $resume = Resume::find($request->id);
            if ($resume && Auth::check() && $resume->user_id !== Auth::id()) {
                $resume = null; // Không được sửa CV của người khác
            }
        } elseif (Auth::check()) {
            // Lấy CV mới nhất của user nếu có
            $resume = Resume::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->first();
        }

        // Dữ liệu mẫu chuẩn TopCV nếu chưa có dữ liệu
        $defaultData = [
            'title' => 'CV Ứng Tuyển Chuyên Nghiệp',
            'fullname' => Auth::check() ? Auth::user()->fullname : 'NGUYỄN VĂN A',
            'candidate_title' => 'LẬP TRÌNH VIÊN FULLSTACK / WEB DEVELOPER',
            'email' => Auth::check() ? Auth::user()->email : 'nguyenvana@gmail.com',
            'phone' => Auth::check() && Auth::user()->mobile ? Auth::user()->mobile : '0987 654 321',
            'birthday' => '15/08/1998',
            'gender' => 'Nam',
            'address' => 'Cầu Giấy, Hà Nội',
            'website' => 'https://github.com/nguyenvana',
            'avatar' => Auth::check() && Auth::user()->image 
                ? asset('assets/user/profile_picture/thumb/' . Auth::user()->image) 
                : asset('assets/user/images/avatar_default.png'),
            'theme_color' => '#00b14f', // TopCV signature green
            'template' => 'modern',
            'objective' => 'Với hơn 3 năm kinh nghiệm phát triển các ứng dụng web với Laravel, PHP và Vue.js. Mục tiêu trong 2 năm tới trở thành Technical Lead, đóng góp vào sự phát triển của các dự án quy mô lớn và tối ưu hiệu suất hệ thống cho doanh nghiệp.',
            'experience' => [
                [
                    'company' => 'Công ty Cổ phần Công nghệ ABC',
                    'position' => 'Senior Backend Developer (PHP / Laravel)',
                    'period' => '03/2022 - Hiện tại',
                    'description' => "• Phụ trách thiết kế cơ sở dữ liệu và xây dựng hệ thống Core API cho nền tảng thương mại điện tử với hơn 100.000 người dùng hàng tháng.\n• Tối ưu truy vấn SQL, giảm thời gian phản hồi API từ 800ms xuống còn 150ms.\n• Hướng dẫn và review code cho 4 thành viên Junior trong nhóm."
                ],
                [
                    'company' => 'Công ty TNHH Giải pháp Phần mềm XYZ',
                    'position' => 'Web Developer',
                    'period' => '06/2020 - 02/2022',
                    'description' => "• Xây dựng và bảo trì các website cổng thông tin tuyển dụng, bán hàng bằng Laravel và MySQL.\n• Tích hợp cổng thanh toán trực tuyến VNPAY, MoMo và đơn vị vận chuyển."
                ]
            ],
            'education' => [
                [
                    'school' => 'Đại học Bách Khoa Hà Nội',
                    'major' => 'Công nghệ Thông tin & Khoa học Máy tính',
                    'period' => '2016 - 2020',
                    'description' => 'Tốt nghiệp loại Giỏi (GPA: 3.4/4.0). Giải Nhì cuộc thi Lập trình sinh viên cấp viện năm 2019.'
                ]
            ],
            'skills' => [
                ['name' => 'PHP / Laravel Framework', 'level' => '90'],
                ['name' => 'MySQL / Database Optimization', 'level' => '85'],
                ['name' => 'JavaScript / jQuery / Vue.js', 'level' => '80'],
                ['name' => 'RESTful API & Webhooks', 'level' => '85'],
                ['name' => 'Git / Docker / Linux VPS', 'level' => '75'],
                ['name' => 'Tiếng Anh giao tiếp & Đọc hiểu tài liệu', 'level' => '75']
            ],
            'projects' => [
                [
                    'name' => 'Hệ sinh thái Tuyển dụng & Tìm việc làm trực tuyến',
                    'role' => 'Tech Lead / Lead Developer',
                    'period' => '01/2023 - 08/2023',
                    'description' => 'Phát triển nền tảng kết nối 50.000 ứng viên và 1.200 nhà tuyển dụng. Xây dựng bộ lọc tìm kiếm theo dải lương, công cụ tạo CV online xuất PDF và quản lý pipeline ứng tuyển.'
                ]
            ],
            'certifications' => [
                [
                    'name' => 'Chứng chỉ TOEIC Quốc Tế 750/990',
                    'organization' => 'IIG Việt Nam',
                    'year' => '2021'
                ],
                [
                    'name' => 'Laravel Certified Developer',
                    'organization' => 'Laravel LLC',
                    'year' => '2022'
                ]
            ]
        ];

        return view('front.cv.builder', [
            'resume' => $resume,
            'defaultData' => $defaultData,
        ]);
    }

    /**
     * Lưu CV qua AJAX
     */
    public function save(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'require_login' => true,
                'message' => 'Vui lòng đăng nhập để lưu CV của bạn!'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'fullname' => 'required|max:255',
            'candidate_title' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:50',
        ], [
            'title.required' => 'Vui lòng nhập tên CV.',
            'fullname.required' => 'Họ và tên không được để trống.',
            'candidate_title.required' => 'Vị trí ứng tuyển không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'phone.required' => 'Số điện thoại không được để trống.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $resumeId = $request->input('resume_id');
        $resume = null;
        if (!empty($resumeId)) {
            $resume = Resume::where('id', $resumeId)->where('user_id', Auth::id())->first();
        }

        if (!$resume) {
            $resume = new Resume();
            $resume->user_id = Auth::id();
        }

        $resume->title = $request->input('title', 'CV Chưa đặt tên');
        $resume->fullname = $request->input('fullname');
        $resume->candidate_title = $request->input('candidate_title');
        $resume->email = $request->input('email');
        $resume->phone = $request->input('phone');
        $resume->birthday = $request->input('birthday');
        $resume->gender = $request->input('gender');
        $resume->address = $request->input('address');
        $resume->website = $request->input('website');
        $resume->avatar = $request->input('avatar');
        $resume->theme_color = $request->input('theme_color', '#00b14f');
        $resume->template = $request->input('template', 'modern');
        $resume->objective = $request->input('objective');

        // Parse JSON data arrays
        $resume->experience_data = $request->input('experience_data') ? json_decode($request->input('experience_data'), true) : [];
        $resume->education_data = $request->input('education_data') ? json_decode($request->input('education_data'), true) : [];
        $resume->skills_data = $request->input('skills_data') ? json_decode($request->input('skills_data'), true) : [];
        $resume->projects_data = $request->input('projects_data') ? json_decode($request->input('projects_data'), true) : [];
        $resume->certifications_data = $request->input('certifications_data') ? json_decode($request->input('certifications_data'), true) : [];

        $resume->save();

        return response()->json([
            'status' => true,
            'resume_id' => $resume->id,
            'message' => 'Lưu CV thành công!'
        ]);
    }

    /**
     * Tải ảnh đại diện cho CV
     */
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'avatar_file.required' => 'Vui lòng chọn ảnh.',
            'avatar_file.image' => 'Tệp phải là hình ảnh.',
            'avatar_file.mimes' => 'Hình ảnh phải có đuôi: jpeg, png, jpg, webp.',
            'avatar_file.max' => 'Kích thước ảnh không vượt quá 3MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first('avatar_file')
            ]);
        }

        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $filename = 'cv_avatar_' . (Auth::id() ?? 'guest') . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('assets/user/cv_avatars');

            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }

            $file->move($destPath, $filename);

            return response()->json([
                'status' => true,
                'avatar_url' => asset('assets/user/cv_avatars/' . $filename),
                'message' => 'Cập nhật ảnh đại diện CV thành công!'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy tệp tải lên.'
        ]);
    }

    /**
     * Danh sách CV đã tạo của ứng viên
     */
    public function myResumes()
    {
        $resumes = Resume::where('user_id', Auth::id())
            ->orderBy('updated_at', 'DESC')
            ->paginate(6);

        return view('front.cv.my_resumes', [
            'resumes' => $resumes
        ]);
    }

    /**
     * Trang xem trước CV và tải PDF (Tối ưu in A4)
     */
    public function printPdf($id)
    {
        $resume = Resume::findOrFail($id);

        return view('front.cv.print', [
            'resume' => $resume
        ]);
    }

    /**
     * Xem trước CV
     */
    public function preview($id)
    {
        $resume = Resume::findOrFail($id);

        return view('front.cv.preview', [
            'resume' => $resume
        ]);
    }

    /**
     * Xóa CV
     */
    public function delete($id)
    {
        $resume = Resume::where('id', $id)->where('user_id', Auth::id())->first();

        if ($resume) {
            $resume->delete();
            return redirect()->back()->with('toastr', ['success' => 'Đã xóa CV thành công!']);
        }

        return redirect()->back()->with('toastr', ['error' => 'Không tìm thấy CV cần xóa!']);
    }
}

