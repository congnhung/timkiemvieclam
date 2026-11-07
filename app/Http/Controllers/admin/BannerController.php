<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::orderBy('sort_order', 'ASC')->orderBy('created_at', 'DESC');
        if (!empty($request->keyword)) {
            $query = $query->where('title', 'like', '%' . $request->keyword . '%');
        }
        $banners = $query->paginate(10);
        return view('admin.banner.list', compact('banners'));
    }

    public function postCreateBanner(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'status' => 'required|in:0,1',
                'sort_order' => 'nullable|integer',
            ],
            [
                'title.required' => 'Vui lòng nhập tiêu đề hoặc tên nhà tuyển dụng.',
                'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
                'image.required' => 'Vui lòng chọn hình ảnh cho banner.',
                'image.image' => 'Tệp tải lên phải là hình ảnh hợp lệ.',
                'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, webp, gif.',
                'image.max' => 'Kích thước hình ảnh tối đa là 5MB.',
                'status.required' => 'Vui lòng chọn trạng thái hiển thị.',
            ]
        );

        if ($validator->passes()) {
            $banner = new Banner();
            $banner->title = $request->title;
            $banner->link = $request->link;
            $banner->status = $request->status;
            $banner->sort_order = $request->sort_order ?? 0;

            if ($request->hasFile('image')) {
                $dir = public_path('assets/user/images/banners');
                if (!File::isDirectory($dir)) {
                    File::makeDirectory($dir, 0777, true, true);
                }
                $file = $request->file('image');
                $filename = 'banner-' . time() . '-' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $file->move($dir, $filename);
                $banner->image = 'assets/user/images/banners/' . $filename;
            }

            $banner->save();
            toastr()->success('Thêm banner quảng cáo thành công.', ' ');
            return redirect()->route('admin.banner.index');
        }

        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function getEditBanner($id)
    {
        if (!ctype_digit((string) $id)) {
            return view('errors.404');
        }
        $banner = Banner::findOrFail($id);
        $banners = Banner::orderBy('sort_order', 'ASC')->orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.banner.edit', compact('banner', 'banners'));
    }

    public function postEditBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'status' => 'required|in:0,1',
                'sort_order' => 'nullable|integer',
            ],
            [
                'title.required' => 'Vui lòng nhập tiêu đề hoặc tên nhà tuyển dụng.',
                'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
                'image.image' => 'Tệp tải lên phải là hình ảnh hợp lệ.',
                'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, webp, gif.',
                'image.max' => 'Kích thước hình ảnh tối đa là 5MB.',
                'status.required' => 'Vui lòng chọn trạng thái hiển thị.',
            ]
        );

        if ($validator->passes()) {
            $banner->title = $request->title;
            $banner->link = $request->link;
            $banner->status = $request->status;
            $banner->sort_order = $request->sort_order ?? 0;

            if ($request->hasFile('image')) {
                $dir = public_path('assets/user/images/banners');
                if (!File::isDirectory($dir)) {
                    File::makeDirectory($dir, 0777, true, true);
                }
                // Xóa ảnh cũ nếu là ảnh trong thư mục upload
                if (!empty($banner->image) && str_starts_with($banner->image, 'assets/user/images/banners/')) {
                    $oldPath = public_path($banner->image);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                $file = $request->file('image');
                $filename = 'banner-' . time() . '-' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $file->move($dir, $filename);
                $banner->image = 'assets/user/images/banners/' . $filename;
            }

            $banner->save();
            toastr()->success('Cập nhật banner quảng cáo thành công.', ' ');
            return redirect()->route('admin.banner.index');
        }

        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function deleteBanner($id)
    {
        $banner = Banner::find($id);
        if ($banner == null) {
            toastr()->error('Banner không tồn tại.', ' ');
            return redirect()->back();
        }

        // Xóa file ảnh nếu được upload trong banners folder
        if (!empty($banner->image) && str_starts_with($banner->image, 'assets/user/images/banners/')) {
            $path = public_path($banner->image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $banner->delete();
        toastr()->success('Xóa banner thành công.', ' ');
        return redirect()->route('admin.banner.index');
    }
}
