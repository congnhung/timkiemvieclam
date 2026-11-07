@extends('front.layouts.app')

@section('main')
  <section class="section-5 bg-2 py-5">
    <div class="container">
      <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
          @include('front.account.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
          <div class="card border-0 shadow mb-4 p-4">
            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-4">
              <div>
                <h4 class="fw-bold text-dark mb-1">
                  <i class="fa fa-file-text-o text-success me-2"></i>CV đã tạo của tôi
                </h4>
                <p class="text-muted small mb-0">Quản lý các bản CV chuyên nghiệp chuẩn TopCV của bạn</p>
              </div>
              <a href="{{ route('cv.builder') }}" class="btn btn-topcv-primary">
                <i class="fa fa-plus me-1"></i> Tạo CV mới
              </a>
            </div>

            @if ($resumes->isNotEmpty())
              <div class="row g-4">
                @foreach ($resumes as $resume)
                  <div class="col-md-6">
                    <div class="card border shadow-sm h-100" style="border-radius: 12px; transition: all 0.2s;">
                      <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                          <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge" style="background-color: {{ $resume->theme_color ?? '#00b14f' }}; color: #ffffff;">
                              Mẫu Hiện Đại
                            </span>
                            <span class="small text-muted">
                              Cập nhật: {{ \Carbon\Carbon::parse($resume->updated_at)->diffForHumans() }}
                            </span>
                          </div>

                          <h5 class="fw-bold mb-1 text-truncate" title="{{ $resume->title }}" style="color: var(--topcv-text-dark);">
                            {{ $resume->title }}
                          </h5>

                          <p class="text-muted small mb-2 text-truncate">
                            <i class="fa fa-user me-1"></i>{{ $resume->fullname }} 
                            @if ($resume->candidate_title)
                              - <span class="fw-semibold">{{ $resume->candidate_title }}</span>
                            @endif
                          </p>

                          <div class="small text-muted mb-3">
                            <span class="me-3"><i class="fa fa-envelope-o me-1"></i>{{ $resume->email }}</span>
                            <span><i class="fa fa-phone me-1"></i>{{ $resume->phone }}</span>
                          </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                          <a href="{{ route('cv.builder', ['id' => $resume->id]) }}" class="btn btn-sm btn-outline-success flex-grow-1 fw-semibold">
                            <i class="fa fa-pencil me-1"></i> Chỉnh sửa
                          </a>

                          <a href="{{ route('cv.print', $resume->id) }}" target="_blank" class="btn btn-sm btn-outline-primary flex-grow-1 fw-semibold">
                            <i class="fa fa-download me-1"></i> Tải PDF
                          </a>

                          <form action="{{ route('cv.delete', $resume->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa bản CV này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa CV">
                              <i class="fa fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <!-- Pagination -->
              <div class="mt-4 d-flex justify-content-center">
                {{ $resumes->links() }}
              </div>
            @else
              <!-- Empty state -->
              <div class="text-center py-5">
                <div class="mb-3">
                  <i class="fa fa-file-text-o text-muted" style="font-size: 54px; opacity: 0.3;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Bạn chưa tạo bản CV nào</h5>
                <p class="text-muted mx-auto mb-4" style="max-width: 420px;">
                  Sử dụng công cụ TopCV Builder với đầy đủ các mục chuẩn, màu sắc chuyên nghiệp và tải file PDF chất lượng cao hoàn toàn miễn phí.
                </p>
                <a href="{{ route('cv.builder') }}" class="btn btn-topcv-primary px-4 py-2">
                  <i class="fa fa-magic me-1"></i> Bắt đầu tạo CV ngay
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

