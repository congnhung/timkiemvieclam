@extends('admin.admin_master')

@section('content')
<style>
  .cv-stat-card {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: transform 0.2s, box-shadow 0.2s;
    background: #fff;
  }
  .cv-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
  }
  .cv-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
  }
  .nav-tabs-cv .nav-link {
    font-weight: 600;
    font-size: 14px;
    color: #64748b;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 12px 20px;
    transition: all 0.2s;
  }
  .nav-tabs-cv .nav-link:hover {
    color: #00b14f;
  }
  .nav-tabs-cv .nav-link.active {
    color: #00b14f !important;
    border-bottom: 2px solid #00b14f !important;
    background: transparent !important;
  }
  .btn-topwork {
    background: #00b14f;
    border-color: #00b14f;
    color: #fff;
    font-weight: 600;
  }
  .btn-topwork:hover {
    background: #009140;
    border-color: #009140;
    color: #fff;
  }
  .cv-color-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    border: 1.5px solid #fff;
    box-shadow: 0 0 0 1px #cbd5e1;
  }
  .tpl-card {
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    background: #fff;
    height: 100%;
    display: flex;
    flex-direction: column;
  }
  .tpl-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 28px rgba(0,0,0,0.1);
    border-color: #00b14f;
  }
  .tpl-card-header {
    height: 80px;
    padding: 16px;
    color: #fff;
    position: relative;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
  }
  .tpl-card-body {
    padding: 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }
</style>

<!-- Tiêu đề trang -->
<div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column justify-content-between gap-2 mb-3">
  <div>
    <h4 class="fs-18 fw-bold mb-1 text-dark">Hệ thống Quản lý CV</h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 fs-12">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý CV</li>
      </ol>
    </nav>
  </div>
  <div>
    <a href="{{ route('cv.builder') }}" target="_blank" class="btn btn-topwork btn-sm px-3 py-1.5 shadow-sm">
      <i class="ti ti-plus me-1"></i> Tạo CV Mới
    </a>
  </div>
</div>

<!-- 3 Thẻ thống kê (KPIs) -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card cv-stat-card mb-0">
      <div class="card-body p-3 d-flex align-items-center gap-3">
        <div class="cv-stat-icon bg-success-subtle text-success">
          <i class="ti ti-file-text"></i>
        </div>
        <div>
          <div class="text-muted fs-12 fw-medium">Tổng hồ sơ CV đã tạo</div>
          <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalResumes) }}</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card cv-stat-card mb-0">
      <div class="card-body p-3 d-flex align-items-center gap-3">
        <div class="cv-stat-icon bg-primary-subtle text-primary">
          <i class="ti ti-calendar-event"></i>
        </div>
        <div>
          <div class="text-muted fs-12 fw-medium">CV tạo trong tháng này</div>
          <h4 class="fw-bold mb-0 text-dark">{{ number_format($thisMonthResumes) }}</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card cv-stat-card mb-0">
      <div class="card-body p-3 d-flex align-items-center gap-3">
        <div class="cv-stat-icon bg-warning-subtle text-warning">
          <i class="ti ti-palette"></i>
        </div>
        <div>
          <div class="text-muted fs-12 fw-medium">Mẫu phong cách khả dụng</div>
          <h4 class="fw-bold mb-0 text-dark">{{ $totalTemplates }} Mẫu</h4>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Khung nội dung chính với 2 TAB (Option 3) -->
<div class="card border-0 shadow-sm rounded-3">
  <div class="card-header bg-white border-bottom p-0">
    <ul class="nav nav-tabs nav-tabs-cv px-3" role="tablist">
      <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'resumes' ? 'active' : '' }}" href="{{ route('admin.cv.index', ['tab' => 'resumes']) }}">
          <i class="ti ti-users me-1.5"></i> Hồ sơ CV Ứng viên
          <span class="badge bg-success-subtle text-success rounded-pill ms-1.5 fs-11">{{ $resumes->total() }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'templates' ? 'active' : '' }}" href="{{ route('admin.cv.index', ['tab' => 'templates']) }}">
          <i class="ti ti-template me-1.5"></i> Mẫu phong cách CV
          <span class="badge bg-warning-subtle text-warning rounded-pill ms-1.5 fs-11">{{ $totalTemplates }}</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="card-body p-3 p-md-4">
    @if ($activeTab === 'resumes')
      {{-- ==========================================
           TAB 1: HỒ SƠ CV CỦA ỨNG VIÊN
           ========================================== --}}
      <!-- Bộ lọc và tìm kiếm -->
      <form action="{{ route('admin.cv.index') }}" method="GET" class="row g-2 mb-3">
        <input type="hidden" name="tab" value="resumes">
        <div class="col-md-4">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light border-end-0"><i class="ti ti-search text-muted"></i></span>
            <input type="text" name="keyword" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên ứng viên, email, tiêu đề CV..." value="{{ Request::get('keyword') }}">
          </div>
        </div>
        <div class="col-md-3">
          <select name="template" class="form-select form-select-sm">
            <option value="all">--- Tất cả mẫu CV ---</option>
            @foreach ($allTemplates as $k => $t)
              <option value="{{ $k }}" {{ Request::get('template') === $k ? 'selected' : '' }}>{{ $t['name'] }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-topwork btn-sm"><i class="ti ti-filter me-1"></i> Lọc</button>
          <a href="{{ route('admin.cv.index', ['tab' => 'resumes']) }}" class="btn btn-outline-secondary btn-sm">Đặt lại</a>
        </div>
      </form>

      <!-- Bảng danh sách CV -->
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
          <thead class="table-light">
            <tr>
              <th style="width: 50px;">#</th>
              <th>Ứng viên</th>
              <th>Tiêu đề hồ sơ</th>
              <th>Phong cách mẫu</th>
              <th>Màu chủ đạo</th>
              <th>Cập nhật</th>
              <th class="text-center" style="width: 160px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($resumes as $idx => $cv)
              @php
                $tplInfo = $allTemplates[$cv->template] ?? null;
                $avatarImg = !empty($cv->avatar) 
                    ? (Str::startsWith($cv->avatar, 'http') ? $cv->avatar : asset('assets/user/cv_avatars/' . $cv->avatar)) 
                    : asset('assets/user/images/avatar_silhouette.svg');
              @endphp
              <tr>
                <td class="text-muted fs-12">{{ $resumes->firstItem() + $idx }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ $avatarImg }}" alt="{{ $cv->fullname }}" class="rounded-circle border" style="width: 38px; height: 38px; object-fit: cover;">
                    <div>
                      <div class="fw-bold text-dark">{{ $cv->fullname }}</div>
                      <div class="text-muted fs-11">{{ $cv->email }} @if($cv->phone) · {{ $cv->phone }} @endif</div>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="fw-semibold text-dark">{{ $cv->title ?: 'CV không có tiêu đề' }}</div>
                  @if($cv->candidate_title)
                    <div class="text-muted fs-11">{{ $cv->candidate_title }}</div>
                  @endif
                </td>
                <td>
                  @if ($tplInfo)
                    <span class="badge bg-light text-dark border py-1 px-2 fw-medium">
                      {{ $tplInfo['name'] }}
                    </span>
                  @else
                    <span class="badge bg-secondary-subtle text-secondary py-1 px-2">{{ $cv->template }}</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex align-items-center gap-1.5">
                    <span class="cv-color-dot" style="background: {{ $cv->theme_color ?: '#00b14f' }};"></span>
                    <span class="fs-11 text-muted">{{ $cv->theme_color ?: '#00b14f' }}</span>
                  </div>
                </td>
                <td>
                  <div class="text-dark">{{ $cv->updated_at->format('d/m/Y') }}</div>
                  <div class="text-muted fs-11">{{ $cv->updated_at->diffForHumans() }}</div>
                </td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    <a href="{{ route('cv.preview', $cv->id) }}" target="_blank" class="btn btn-outline-info" title="Xem trước CV">
                      <i class="ti ti-eye"></i>
                    </a>
                    <a href="{{ route('cv.print', $cv->id) }}" target="_blank" class="btn btn-outline-success" title="Tải PDF">
                      <i class="ti ti-download"></i>
                    </a>
                    <a href="{{ route('cv.builder', ['id' => $cv->id]) }}" target="_blank" class="btn btn-outline-primary" title="Chỉnh sửa nội dung">
                      <i class="ti ti-pencil"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger" title="Xóa CV" onclick="confirmDeleteCv('{{ $cv->id }}', '{{ addslashes($cv->fullname) }}')">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                  <form id="delete-cv-form-{{ $cv->id }}" action="{{ route('admin.cv.destroy', $cv->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                  <i class="ti ti-file-search fs-36 text-secondary d-block mb-2"></i>
                  Chưa có hồ sơ CV nào phù hợp với điều kiện tìm kiếm.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Phân trang -->
      @if ($resumes->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
          <div class="text-muted fs-12">
            Hiển thị từ {{ $resumes->firstItem() }} đến {{ $resumes->lastItem() }} trên tổng số {{ $resumes->total() }} hồ sơ
          </div>
          <div>
            {{ $resumes->links('pagination::bootstrap-5') }}
          </div>
        </div>
      @endif

    @else
      {{-- ==========================================
           TAB 2: MẪU PHONG CÁCH CV (12 TEMPLATES)
           ========================================== --}}
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h5 class="fw-bold mb-1 text-dark">Danh sách 12 Phong cách Mẫu CV Chuẩn Nhà Tuyển Dụng</h5>
          <p class="text-muted fs-12 mb-0">Các mẫu CV được tối ưu theo 4 cấu trúc bố cục: Cột bên (Sidebar), Banner ngang, Header phân tách, và Chuẩn 1 cột quét ATS.</p>
        </div>
      </div>

      <div class="row g-3">
        @foreach ($allTemplates as $k => $tpl)
          @php
            $layoutLabels = [
              'sidebar_left' => 'Cột bên (Sidebar Left)',
              'header_banner' => 'Banner ngang trên đỉnh',
              'header_split' => 'Header phân tách 2 cột',
              'single_column' => '1 Cột tối giản (Chuẩn ATS)',
            ];
            $layoutName = $layoutLabels[$tpl['layout'] ?? 'sidebar_left'] ?? 'Cột bên';
          @endphp
          <div class="col-md-6 col-xl-4">
            <div class="tpl-card">
              <div class="tpl-card-header" style="background: {{ $tpl['primary_color'] }};">
                <div>
                  <span class="badge bg-{{ $tpl['badge_color'] ?? 'light' }} text-uppercase fs-10 fw-bold px-2 py-1">
                    {{ $tpl['badge'] }}
                  </span>
                  <div class="fs-11 mt-1 opacity-75">Layout: {{ $tpl['layout'] }}</div>
                </div>
                <div class="d-flex align-items-center gap-1.5">
                  <span class="cv-color-dot" style="background: {{ $tpl['accent_color'] }};" title="Màu nhấn"></span>
                </div>
              </div>
              <div class="tpl-card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h6 class="fw-bold text-dark mb-0 fs-15">{{ $tpl['name'] }}</h6>
                  <span class="badge bg-light text-muted border fs-10">{{ $tpl['category_name'] }}</span>
                </div>
                <p class="text-muted fs-12 mb-3" style="min-height: 38px; line-height: 1.45;">
                  {{ $tpl['description'] }}
                </p>
                <div class="border-top pt-2 mt-auto fs-11 text-muted d-flex justify-content-between align-items-center">
                  <div>
                    <i class="ti ti-typography me-1"></i> Font: <strong>{{ $tpl['font'] }}</strong>
                  </div>
                  <div>
                    <i class="ti ti-layout me-1"></i> {{ $layoutName }}
                  </div>
                </div>
                <div class="pt-3">
                  <a href="{{ route('cv.builder', ['template' => $k]) }}" target="_blank" class="btn btn-outline-success btn-sm w-100 fw-semibold">
                    <i class="ti ti-external-link me-1"></i> Trải nghiệm mẫu này
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>

<script>
  function confirmDeleteCv(id, name) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Xóa hồ sơ CV?',
        text: 'Bạn có chắc chắn muốn xóa hồ sơ CV của ứng viên "' + name + '" không? Hành động này không thể hoàn tác.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Đồng ý xóa',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-cv-form-' + id).submit();
        }
      });
    } else {
      if (confirm('Bạn có chắc chắn muốn xóa hồ sơ CV của ứng viên "' + name + '"?')) {
        document.getElementById('delete-cv-form-' + id).submit();
      }
    }
  }
</script>
@endsection
