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
     * Danh sách 12 mẫu CV chuẩn nhà tuyển dụng
     */
    public static function getTemplatesList()
    {
        return [
            'modern_navy' => [
                'id' => 'modern_navy',
                'name' => 'Hiện Đại (Navy)',
                'badge' => 'HOT',
                'badge_color' => 'danger',
                'category' => 'modern',
                'category_name' => 'Hiện đại',
                'tags' => ['Hiện đại', 'Chuyên nghiệp'],
                'description' => 'Thiết kế cột bên màu xanh navy sang trọng, tạo cảm giác tin cậy và vững chắc.',
                'primary_color' => '#0f2942',
                'accent_color' => '#38bdf8',
                'font' => 'Lexend',
                'layout' => 'sidebar_left',
            ],
            'teal_mint' => [
                'id' => 'teal_mint',
                'name' => 'Thanh Lịch (Teal Mint)',
                'badge' => 'MỚI',
                'badge_color' => 'success',
                'category' => 'elegant',
                'category_name' => 'Thanh lịch',
                'tags' => ['Thanh lịch', 'Đơn giản'],
                'description' => 'Tông màu xanh ngọc dịu mắt, đường viền mềm mại, phù hợp mọi ngành nghề.',
                'primary_color' => '#0d9488',
                'accent_color' => '#5eead4',
                'font' => 'Inter',
                'layout' => 'header_banner',
            ],
            'executive_dark' => [
                'id' => 'executive_dark',
                'name' => 'Chuyên Nghiệp (Executive)',
                'badge' => 'HOT',
                'badge_color' => 'danger',
                'category' => 'professional',
                'category_name' => 'Chuyên nghiệp',
                'tags' => ['Chuyên nghiệp', 'Quản lý'],
                'description' => 'Bố cục chuẩn mực cho cấp Senior, Trưởng nhóm và Giám đốc với tone xám than quyền lực.',
                'primary_color' => '#1e293b',
                'accent_color' => '#f59e0b',
                'font' => 'Roboto',
                'layout' => 'sidebar_left',
            ],
            'minimalist_clean' => [
                'id' => 'minimalist_clean',
                'name' => 'Đơn Giản Tinh Tế',
                'badge' => 'ƯA THÍCH',
                'badge_color' => 'primary',
                'category' => 'simple',
                'category_name' => 'Đơn giản',
                'tags' => ['Đơn giản', 'Chuẩn ATS'],
                'description' => 'Thiết kế 1 cột tối giản phong cách Bắc Âu, tương thích 100% với hệ thống quét ATS.',
                'primary_color' => '#2563eb',
                'accent_color' => '#64748b',
                'font' => 'Inter',
                'layout' => 'single_column',
            ],
            'creative_coral' => [
                'id' => 'creative_coral',
                'name' => 'Sáng Tạo (Creative)',
                'badge' => 'MỚI',
                'badge_color' => 'warning',
                'category' => 'creative',
                'category_name' => 'Sáng tạo',
                'tags' => ['Sáng tạo', 'Marketing'],
                'description' => 'Điểm nhấn màu cam san hô rực rỡ, thích hợp cho Designer, Copywriter, Marketing.',
                'primary_color' => '#ea580c',
                'accent_color' => '#fdba74',
                'font' => 'Montserrat',
                'layout' => 'header_split',
            ],
            'tech_slate' => [
                'id' => 'tech_slate',
                'name' => 'IT & Lập Trình Viên',
                'badge' => 'HOT',
                'badge_color' => 'danger',
                'category' => 'it',
                'category_name' => 'IT & Công nghệ',
                'tags' => ['IT & Tech', 'Lập trình'],
                'description' => 'Bố cục dạng block kỹ năng công nghệ rõ ràng, làm nổi bật dự án GitHub và tech stack.',
                'primary_color' => '#0284c7',
                'accent_color' => '#38bdf8',
                'font' => 'Roboto',
                'layout' => 'sidebar_left',
            ],
            'classic_serif' => [
                'id' => 'classic_serif',
                'name' => 'Cổ Điển Sang Trọng',
                'badge' => 'CHUẨN',
                'badge_color' => 'secondary',
                'category' => 'classic',
                'category_name' => 'Cổ điển',
                'tags' => ['Cổ điển', 'Học thuật'],
                'description' => 'Kiểu chữ có chân truyền thống, lịch sự, rất được lòng khối Giáo dục, Pháp lý, Tài chính.',
                'primary_color' => '#475569',
                'accent_color' => '#94a3b8',
                'font' => 'Georgia',
                'layout' => 'header_split',
            ],
            'fresh_graduate' => [
                'id' => 'fresh_graduate',
                'name' => 'Sinh Viên & Mới Ra Trường',
                'badge' => 'PHỔ BIẾN',
                'badge_color' => 'info',
                'category' => 'simple',
                'category_name' => 'Đơn giản',
                'tags' => ['Sinh viên', 'Mới tốt nghiệp'],
                'description' => 'Tập trung nêu bật học vấn, hoạt động ngoại khóa, kỹ năng mềm và tiềm năng phát triển.',
                'primary_color' => '#059669',
                'accent_color' => '#34d399',
                'font' => 'Lexend',
                'layout' => 'sidebar_left',
            ],
            'royal_purple' => [
                'id' => 'royal_purple',
                'name' => 'Thời Thượng (Royal)',
                'badge' => 'VIP',
                'badge_color' => 'dark',
                'category' => 'modern',
                'category_name' => 'Hiện đại',
                'tags' => ['Thời thượng', 'Cao cấp'],
                'description' => 'Sắc tím quý phái phối cùng nền trắng cao cấp, tạo ấn tượng mạnh mẽ ngay từ 3 giây đầu.',
                'primary_color' => '#7c3aed',
                'accent_color' => '#c084fc',
                'font' => 'Montserrat',
                'layout' => 'header_banner',
            ],
            'emerald_startup' => [
                'id' => 'emerald_startup',
                'name' => 'Năng Động (Startup)',
                'badge' => 'MỚI',
                'badge_color' => 'success',
                'category' => 'modern',
                'category_name' => 'Hiện đại',
                'tags' => ['Hiện đại', 'Startup'],
                'description' => 'Màu xanh lục bảo tươi sáng, thể hiện tinh thần bứt phá và thích nghi cao.',
                'primary_color' => '#00b14f',
                'accent_color' => '#4ade80',
                'font' => 'Lexend',
                'layout' => 'sidebar_left',
            ],
            'burgundy_luxury' => [
                'id' => 'burgundy_luxury',
                'name' => 'Đẳng Cấp (Burgundy)',
                'badge' => 'HOT',
                'badge_color' => 'danger',
                'category' => 'professional',
                'category_name' => 'Chuyên nghiệp',
                'tags' => ['Chuyên nghiệp', 'Tài chính'],
                'description' => 'Tone đỏ đô trầm ấm, tinh tế, phù hợp cho ngành Bất động sản, Bảo hiểm, Ngân hàng.',
                'primary_color' => '#881337',
                'accent_color' => '#fb7185',
                'font' => 'Roboto',
                'layout' => 'header_split',
            ],
            'ats_standard' => [
                'id' => 'ats_standard',
                'name' => 'Chuẩn Quốc Tế (ATS 100%)',
                'badge' => 'ATS CHUẨN',
                'badge_color' => 'dark',
                'category' => 'simple',
                'category_name' => 'Đơn giản',
                'tags' => ['Chuẩn ATS', 'Quốc tế'],
                'description' => 'Không đồ họa phức tạp, định dạng bảng chuẩn mực, tỷ lệ quét đậu ATS lên đến 99%.',
                'primary_color' => '#18181b',
                'accent_color' => '#71717a',
                'font' => 'Arial',
                'layout' => 'single_column',
            ],
        ];
    }

    /**
     * Màn hình danh sách tất cả mẫu CV (Gallery)
     */
    public function templates(Request $request)
    {
        $allTemplates = self::getTemplatesList();
        $category = $request->get('category', 'all');

        $templates = $allTemplates;
        if ($category !== 'all') {
            $templates = array_filter($allTemplates, function ($t) use ($category) {
                return $t['category'] === $category;
            });
        }

        return view('front.cv.templates', [
            'templates' => $templates,
            'allTemplates' => $allTemplates,
            'currentCategory' => $category,
        ]);
    }

    /**
     * Màn hình tạo và chỉnh sửa CV Online
     */
    public function index(Request $request)
    {
        $resume = null;
        // Chỉ tải CV cũ nếu có truyền ID cụ thể trên URL
        if ($request->has('id')) {
            $resume = Resume::find($request->id);
            if ($resume && Auth::check() && $resume->user_id !== Auth::id()) {
                $resume = null; // Không được sửa CV của người khác
            }
        }

        $allTemplates = self::getTemplatesList();
        $selectedTemplateKey = $request->get('template', $resume->template ?? 'modern_navy');
        if (!isset($allTemplates[$selectedTemplateKey])) {
            $selectedTemplateKey = 'modern_navy';
        }
        $selectedTemplate = $allTemplates[$selectedTemplateKey];

        $user = Auth::user();
        $defaultFullName = $user ? ($user->fullname ?? $user->name) : 'Nhung Nguyễn Công';
        $defaultEmail = $user ? $user->email : 'congnhungdz2@gmail.com';
        $defaultPhone = $user && $user->mobile ? $user->mobile : '';
        $defaultAvatar = $user && $user->image 
            ? asset('assets/user/profile_picture/thumb/' . $user->image) 
            : asset('assets/user/images/avatar_silhouette.svg');

        // Khởi tạo form trống chuẩn vieclam24h (chỉ lấy Tên và Email của tài khoản)
        $defaultData = [
            'title' => 'CV ' . $defaultFullName,
            'fullname' => $defaultFullName,
            'candidate_title' => '',
            'email' => $defaultEmail,
            'phone' => $defaultPhone,
            'birthday' => '',
            'gender' => '',
            'address' => '',
            'website' => '',
            'avatar' => $defaultAvatar,
            'theme_color' => $selectedTemplate['primary_color'] ?? '#004C82',
            'template' => $selectedTemplateKey,
            'font' => $selectedTemplate['font'] ?? 'Lexend',
            'objective' => '',
            'experience' => [],
            'education' => [],
            'skills' => [],
            'projects' => [],
            'certifications' => []
        ];

        // Nếu người dùng chọn template cụ thể qua URL
        if ($request->has('template') && isset($allTemplates[$request->template])) {
            $defaultData['template'] = $request->template;
            $defaultData['theme_color'] = $selectedTemplate['primary_color'];
            $defaultData['font'] = $selectedTemplate['font'];
        }

        return view('front.cv.builder', [
            'resume' => $resume,
            'defaultData' => $defaultData,
            'allTemplates' => $allTemplates,
            'selectedTemplate' => $selectedTemplate,
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

