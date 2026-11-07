<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    /**
     * Lấy danh sách 34 Tỉnh / Thành phố mới theo Nghị quyết 202/2025/QH15
     */
    public function getProvinces()
    {
        $provinces = config('vietnam_provinces_34.provinces', []);

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'total' => count($provinces),
            'data' => $provinces
        ]);
    }

    /**
     * Mô hình 2 cấp: Đã bỏ cấp Quận / Huyện.
     * Trả về mảng rỗng để tương thích ngược.
     */
    public function getDistricts($provinceId)
    {
        return response()->json([
            'error' => 0,
            'message' => 'Cấp quận/huyện đã được bãi bỏ theo mô hình 2 cấp',
            'total' => 0,
            'data' => []
        ]);
    }

    /**
     * Lấy danh sách Xã / Phường trực thuộc Tỉnh theo mô hình 2 cấp mới.
     * Hỗ trợ tìm kiếm theo:
     * - open_api_code / code (vd: 1, 79, 19...)
     * - Tên tỉnh rút gọn (vd: "Hà Nội", "Thái Nguyên", "Bình Định"...)
     * - Tên tỉnh đầy đủ (vd: "Thành phố Hà Nội", "Tỉnh Thái Nguyên"...)
     */
    public function getWards($provinceQuery)
    {
        $cacheKey = 'wards_34_p_' . md5($provinceQuery);
        $data = Cache::rememberForever($cacheKey, function () use ($provinceQuery) {
            $filePath = resource_path('data/vietnam_administrative_34.json');
            if (!file_exists($filePath)) {
                return [];
            }

            $administrativeData = json_decode(file_get_contents($filePath), true);
            if (!is_array($administrativeData)) {
                return [];
            }

            $cleanQuery = trim($provinceQuery);

            // 1. Tìm theo mã code số
            if (is_numeric($cleanQuery)) {
                foreach ($administrativeData as $p) {
                    if ($p['code'] == $cleanQuery) {
                        return $p['wards'] ?? [];
                    }
                }
            }

            // 2. Chuẩn hoá chuỗi để so khớp tên
            $normalize = function ($s) {
                $s = mb_strtolower(trim($s));
                $s = preg_replace('/^(thành phố|tỉnh|tp\.?)\s+/ui', '', $s);
                return trim($s);
            };

            $targetNorm = $normalize($cleanQuery);

            foreach ($administrativeData as $p) {
                $pNorm = $normalize($p['name']);
                if ($pNorm === $targetNorm || mb_strpos($p['name'], $cleanQuery) !== false) {
                    return $p['wards'] ?? [];
                }
            }

            // 3. Các trường hợp sáp nhập đặc biệt
            if (mb_strpos($targetNorm, 'bình định') !== false || mb_strpos($targetNorm, 'gia lai') !== false) {
                foreach ($administrativeData as $p) {
                    if ($p['code'] == 52) {
                        return $p['wards'] ?? [];
                    }
                }
            }
            if (mb_strpos($targetNorm, 'hồ chí minh') !== false || mb_strpos($targetNorm, 'hcm') !== false || mb_strpos($targetNorm, 'sài gòn') !== false) {
                foreach ($administrativeData as $p) {
                    if ($p['code'] == 79) {
                        return $p['wards'] ?? [];
                    }
                }
            }

            return [];
        });

        // Chuẩn hoá định dạng trả về đồng nhất cho frontend
        $formattedWards = array_map(function ($w) {
            return [
                'id' => $w['code'] ?? '',
                'code' => $w['code'] ?? '',
                'name' => $w['name'] ?? '',
                'full_name' => $w['name'] ?? '',
                'division_type' => $w['division_type'] ?? ''
            ];
        }, $data);

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'total' => count($formattedWards),
            'data' => $formattedWards
        ]);
    }
}
