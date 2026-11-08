@extends('admin.admin_master')
@section('content')
  <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3">
    <div class="flex-grow-1">
      <h4 class="fs-18 fw-semibold mb-0">Cập nhật Banner quảng cáo</h4>
      <p class="text-muted small mb-0">Chỉnh sửa thông tin banner hoặc thay thế hình ảnh</p>
    </div>
    <div>
      <a href="{{ route('admin.banner.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="ti ti-arrow-left me-1"></i> Quay lại danh sách
      </a>
    </div>
  </div>

  <div class="row">
    <!-- FORM CẬP NHẬT BANNER -->
    <div class="col-xl-4 col-lg-5">
      <div class="card">
        <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Chỉnh sửa Banner #{{ $banner->id }}</h5>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.banner.update', $banner->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label class="form-label" for="title">Tiêu đề / Tên nhà tuyển dụng <span class="text-danger">*</span></label>
              <input type="text" id="title" name="title"
                placeholder="VD: Tập Đoàn FPT Tuyển Dụng Developer"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $banner->title) }}">
              @if ($errors->has('title'))
                <span class="invalid-feedback">{{ $errors->first('title') }}</span>
              @endif
            </div>

            <div class="mb-3">
              <label class="form-label" for="banner_image">Hình ảnh banner</label>
              <input type="file" id="banner_image" name="image" accept="image/*"
                class="form-control @error('image') is-invalid @enderror"
                onchange="previewBanner(this)">
              <div class="form-text small text-muted">Bỏ trống nếu không muốn thay đổi ảnh hiện tại.</div>
              @if ($errors->has('image'))
                <span class="invalid-feedback d-block">{{ $errors->first('image') }}</span>
              @endif

              <!-- Current / Preview image box -->
              <div class="mt-2 text-center" id="imagePreviewBox">
                <div class="small text-muted mb-1">Ảnh hiện tại:</div>
                <img id="imagePreview" src="{{ asset($banner->image) }}" alt="Preview" class="img-fluid rounded border shadow-sm" style="max-height: 140px; width: 100%; object-fit: cover;">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label" for="link">Đường dẫn liên kết (Link URL)</label>
              <input type="text" id="link" name="link"
                placeholder="VD: /jobs?career=13 hoặc link công ty"
                class="form-control @error('link') is-invalid @enderror"
                value="{{ old('link', $banner->link) }}">
              <div class="form-text small text-muted">Khi ứng viên click vào banner sẽ chuyển đến trang này.</div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" for="sort_order">Thứ tự hiển thị</label>
                <input type="number" id="sort_order" name="sort_order"
                  placeholder="1, 2, 3..."
                  class="form-control"
                  value="{{ old('sort_order', $banner->sort_order) }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label" for="status">Trạng thái <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                  <option value="1" {{ old('status', $banner->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                  <option value="0" {{ old('status', $banner->status) == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
              </div>
            </div>

            <div class="d-flex gap-2 mt-2">
              <button class="btn btn-primary flex-grow-1" type="submit">
                <i class="ti ti-device-floppy me-1"></i> Lưu thay đổi
              </button>
              <a href="{{ route('admin.banner.index') }}" class="btn btn-outline-secondary">Hủy</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- DANH SÁCH BANNER -->
    <div class="col-xl-8 col-lg-7">
      <div class="card">
        <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Danh sách Banner</h5>
          <a href="{{ route('admin.banner.index') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus me-1"></i> Thêm mới
          </a>
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
                  @foreach ($banners as $b)
                    <tr class="{{ $b->id == $banner->id ? 'table-active' : '' }}">
                      <td class="fw-semibold text-center">{{ $b->sort_order }}</td>
                      <td>
                        <img src="{{ asset($b->image) }}" alt="{{ $b->title }}"
                          class="rounded border shadow-sm"
                          style="width: 120px; height: 68px; object-fit: cover;">
                      </td>
                      <td>
                        <div class="fw-semibold text-dark">{{ $b->title }}</div>
                        <small class="text-muted">Ngày tạo: {{ \Carbon\Carbon::parse($b->created_at)->format('d/m/Y') }}</small>
                      </td>
                      <td>
                        @if (!empty($b->link))
                          <a href="{{ $b->link }}" target="_blank" class="text-truncate d-inline-block text-primary small" style="max-width: 160px;">
                            {{ $b->link }}
                          </a>
                        @else
                          <span class="text-muted small">Không có</span>
                        @endif
                      </td>
                      <td>
                        @if ($b->status == 1)
                          <span class="badge bg-success">Hiển thị</span>
                        @else
                          <span class="badge bg-secondary">Đang ẩn</span>
                        @endif
                      </td>
                      <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                          <a href="{{ route('admin.banner.edit', $b->id) }}" class="btn btn-sm {{ $b->id == $banner->id ? 'btn-info' : 'btn-outline-info' }}" title="Sửa">
                            <i class="ti ti-pencil"></i>
                          </a>
                          <form action="{{ route('admin.banner.delete', $b->id) }}" method="POST"
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
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function previewBanner(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('imagePreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
@endsection

