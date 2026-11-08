@extends('admin.admin_master')
@section('content')
  <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3">
    <div class="flex-grow-1">
      <h4 class="fs-18 fw-semibold mb-0">Quản lý Banner quảng cáo nhà tuyển dụng</h4>
      <p class="text-muted small mb-0">Thay đổi, thêm mới và quản lý 3 banner quảng cáo hiển thị tại trang chủ</p>
    </div>
    <form action="{{ route('admin.banner.index') }}" class="row row-cols-lg-auto g-2 align-items-center">
      <div class="col">
        <input type="text" class="form-control form-control-sm" name="keyword" placeholder="Tìm theo tiêu đề..."
          value="{{ Request::get('keyword') }}">
      </div>
      <div class="col">
        <button type="submit" class="btn btn-sm btn-success">Tìm kiếm</button>
      </div>
    </form>
  </div>

  <div class="row">
    <!-- FORM THÊM MỚI BANNER -->
    <div class="col-xl-4 col-lg-5">
      <div class="card">
        <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Thêm Banner quảng cáo mới</h5>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.banner.create') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label class="form-label" for="title">Tiêu đề / Tên nhà tuyển dụng <span class="text-danger">*</span></label>
              <input type="text" id="title" name="title"
                placeholder="VD: Tập Đoàn FPT Tuyển Dụng Developer"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title') }}">
              @if ($errors->has('title'))
                <span class="invalid-feedback">{{ $errors->first('title') }}</span>
              @endif
            </div>

            <div class="mb-3">
              <label class="form-label" for="banner_image">Hình ảnh banner <span class="text-danger">*</span></label>
              <input type="file" id="banner_image" name="image" accept="image/*"
                class="form-control @error('image') is-invalid @enderror"
                onchange="previewBanner(this)">
              <div class="form-text small text-muted">Kích thước gợi ý: 800x450px hoặc tỉ lệ 16:9, tối đa 5MB.</div>
              @if ($errors->has('image'))
                <span class="invalid-feedback d-block">{{ $errors->first('image') }}</span>
              @endif

              <!-- Image preview box -->
              <div class="mt-2 text-center" id="imagePreviewBox" style="display: none;">
                <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded border shadow-sm" style="max-height: 140px; width: 100%; object-fit: cover;">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label" for="link">Đường dẫn liên kết (Link URL)</label>
              <input type="text" id="link" name="link"
                placeholder="VD: /jobs?career=13 hoặc link công ty"
                class="form-control @error('link') is-invalid @enderror"
                value="{{ old('link') }}">
              <div class="form-text small text-muted">Khi ứng viên click vào banner sẽ chuyển đến trang này.</div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" for="sort_order">Thứ tự hiển thị</label>
                <input type="number" id="sort_order" name="sort_order"
                  placeholder="1, 2, 3..."
                  class="form-control"
                  value="{{ old('sort_order', 0) }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label" for="status">Trạng thái <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                  <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Hiển thị</option>
                  <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
              </div>
            </div>

            <div class="d-grid mt-2">
              <button class="btn btn-primary" type="submit">
                <i class="ti ti-plus me-1"></i> Thêm banner
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- DANH SÁCH BANNER -->
    <div class="col-xl-8 col-lg-7">
      <div class="card">
        <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Danh sách Banner đang có ({{ $banners->total() }})</h5>
          <span class="badge bg-success-subtle text-success">3 banner đang bật sẽ chạy trên trang chủ</span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover table-centered mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width: 50px;">Thứ tự</th>
                  <th style="width: 140px;">Hình ảnh</th>
                  <th>Tiêu đề / Doanh nghiệp</th>
                  <th>Liên kết</th>
                  <th style="width: 100px;">Trạng thái</th>
                  <th style="width: 90px;" class="text-center">Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @if ($banners->isNotEmpty())
                  @foreach ($banners as $banner)
                    <tr>
                      <td class="fw-semibold text-center">{{ $banner->sort_order }}</td>
                      <td>
                        <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}"
                          class="rounded border shadow-sm"
                          style="width: 120px; height: 68px; object-fit: cover;">
                      </td>
                      <td>
                        <div class="fw-semibold text-dark">{{ $banner->title }}</div>
                        <small class="text-muted">Ngày tạo: {{ \Carbon\Carbon::parse($banner->created_at)->format('d/m/Y') }}</small>
                      </td>
                      <td>
                        @if (!empty($banner->link))
                          <a href="{{ $banner->link }}" target="_blank" class="text-truncate d-inline-block text-primary small" style="max-width: 160px;">
                            {{ $banner->link }}
                          </a>
                        @else
                          <span class="text-muted small">Không có</span>
                        @endif
                      </td>
                      <td>
                        @if ($banner->status == 1)
                          <span class="badge bg-success">Hiển thị</span>
                        @else
                          <span class="badge bg-secondary">Đang ẩn</span>
                        @endif
                      </td>
                      <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                          <a href="{{ route('admin.banner.edit', $banner->id) }}" class="btn btn-sm btn-outline-info" title="Sửa">
                            <i class="ti ti-pencil"></i>
                          </a>
                          <form action="{{ route('admin.banner.delete', $banner->id) }}" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                              <i class="ti ti-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                      Chưa có banner quảng cáo nào. Vui lòng thêm mới ở form bên trái!
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
        @if ($banners->hasPages())
          <div class="card-footer bg-light-subtle d-flex justify-content-end py-2">
            {{ $banners->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    function previewBanner(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('imagePreview').src = e.target.result;
          document.getElementById('imagePreviewBox').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
@endsection

