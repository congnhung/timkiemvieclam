<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\CvBuilderController;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CvManagementController extends Controller
{
    /**
     * Màn hình Quản lý CV trong Admin (Gồm 2 Tab: Hồ sơ CV Ứng viên & Mẫu phong cách CV)
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'resumes');
        $allTemplates = CvBuilderController::getTemplatesList();

        // 1. Thống kê nhanh
        $totalResumes = Resume::count();
        $thisMonthResumes = Resume::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalTemplates = count($allTemplates);

        // 2. Danh sách hồ sơ CV của ứng viên (Tab 1)
        $query = Resume::with('user')->orderBy('updated_at', 'desc');

        if (!empty($request->keyword)) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('fullname', 'like', '%' . $keyword . '%')
                    ->orWhere('title', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('phone', 'like', '%' . $keyword . '%')
                    ->orWhereHas('user', function ($uq) use ($keyword) {
                        $uq->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('fullname', 'like', '%' . $keyword . '%')
                            ->orWhere('email', 'like', '%' . $keyword . '%');
                    });
            });
        }

        if (!empty($request->template) && $request->template !== 'all') {
            $query->where('template', $request->template);
        }

        $resumes = $query->paginate(10)->withQueryString();

        return view('admin.cv.index', [
            'activeTab' => $activeTab,
            'allTemplates' => $allTemplates,
            'resumes' => $resumes,
            'totalResumes' => $totalResumes,
            'thisMonthResumes' => $thisMonthResumes,
            'totalTemplates' => $totalTemplates,
        ]);
    }

    /**
     * Xóa hồ sơ CV của ứng viên
     */
    public function destroy($id)
    {
        $resume = Resume::find($id);
        if (!$resume) {
            toastr()->error('Hồ sơ CV không tồn tại.', 'Lỗi');
            return redirect()->route('admin.cv.index', ['tab' => 'resumes']);
        }

        // Xóa file avatar nếu là file upload cục bộ
        if (!empty($resume->avatar) && !str_starts_with($resume->avatar, 'http')) {
            $avatarPath = public_path('assets/user/cv_avatars/' . $resume->avatar);
            if (File::exists($avatarPath)) {
                File::delete($avatarPath);
            }
        }

        $resume->delete();

        toastr()->success('Đã xóa hồ sơ CV thành công.', 'Thành công');
        return redirect()->route('admin.cv.index', ['tab' => 'resumes']);
    }
}
