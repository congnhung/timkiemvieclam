@extends('front.layouts.app')

@section('main')
  <section class="tw-section tw-section-alt" style="padding-top: var(--sp-6);">
    <div class="container">
      
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: var(--text-sm);">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
          <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Tin tuyển dụng đã đăng</li>
        </ol>
      </nav>

      <div class="row g-4">
        <div class="col-lg-3">
          @include('front.account.sidebar')
        </div>

        <div class="col-lg-9">
          <div class="tw-content-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom">
              <div>
                <h3 class="mb-1 border-0 pb-0">Quản lý tin tuyển dụng</h3>
                <p class="text-muted small mb-0" style="font-size: var(--text-xs);">
                  Danh sách tất cả các bài tuyển dụng bạn đã tạo trên hệ thống.
                </p>
              </div>

              <div>
                <form action="" name="searchForm" id="searchForm" class="d-flex gap-2">
                  <input value="{{ Request::get('keyword') }}" type="text" name="keyword" id="keyword" placeholder="Tìm theo tiêu đề..."
                    class="tw-input" style="font-size: var(--text-sm); min-width: 200px;">
                  <button type="submit" class="tw-btn tw-btn-primary" style="font-size: var(--text-sm);">Tìm</button>
                </form>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table align-middle" style="font-size: var(--text-sm);">
                <thead class="table-light">
                  <tr>
                    <th scope="col" class="fw-semibold">Vị trí</th>
                    <th scope="col" class="fw-semibold">Ngày đăng</th>
                    <th scope="col" class="fw-semibold text-center">Ứng viên / Chỉ tiêu</th>
                    <th scope="col" class="fw-semibold">Trạng thái</th>
                    <th scope="col" class="text-end fw-semibold">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($jobs->isNotEmpty())
                    @foreach ($jobs as $job)
                      <form id="remove-job-form-{{ $job->id }}" action="{{ route('account.deleteJob') }}" method="POST">
                        @csrf
                        <tr>
                          <td style="display: none"><input type="hidden" name="id" value="{{ $job->id }}"></td>
                          <td>
                            <a href="{{ route('JobDetail_employer', $job->id) }}" class="text-decoration-none">
                              <strong class="text-dark d-block">{{ Str::words(strip_tags($job->title), 6) }}</strong>
                            </a>
                            <small class="text-muted">{{ $job->jobType->name }} · {{ $job->province ?? 'Toàn quốc' }}</small>
                          </td>
                          <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d/m/Y') }}</td>
                          <td class="text-center">
                            <a href="{{ route('JobDetail_employer', $job->id) }}" class="text-decoration-none" title="Xem danh sách ứng viên">
                              <span class="badge {{ $job->applications->count() > 0 ? 'bg-success' : 'bg-light text-dark border' }}" style="font-size: 12px;">
                                {{ $job->applications->count() }} hồ sơ
                              </span>
                            </a>
                            <div class="text-muted small" style="font-size: 11px;">Chỉ tiêu: {{ $job->vacancy }}</div>
                          </td>
                          <td>
                            @if ($job->status == 1)
                              <span class="tw-job-badge tw-badge-type">Đã duyệt</span>
                            @elseif ($job->status == 0)
                              <span class="tw-job-badge tw-badge-salary">Đang chờ duyệt</span>
                            @elseif ($job->status == 2)
                              <span class="tw-job-badge tw-badge-loc">Hết hạn</span>
                            @elseif ($job->status == 3)
                              <span class="tw-job-badge tw-badge-loc">Đã đủ số lượng</span>
                            @endif
                          </td>                        
                          <td class="text-end">
                            <div class="dropdown d-inline-block">
                              <button class="tw-btn tw-btn-outline" style="padding: 3px 10px; font-size: var(--text-xs);" data-bs-toggle="dropdown">
                                Tuỳ chọn ▾
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end shadow-sm border" style="font-size: var(--text-sm);">
                                <li>
                                  <a class="dropdown-item" href="{{ route('JobDetail_employer', $job->id) }}">
                                    Xem chi tiết & ứng viên
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item" href="{{ route('account.editJob', $job->id) }}">
                                    Chỉnh sửa
                                  </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                  <button type="button" class="dropdown-item text-danger" onclick="confirmation({{ $job->id }})">
                                    Xoá tin tuyển dụng
                                  </button>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>
                      </form>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="5" class="text-center py-4 text-muted">
                        Chưa có tin tuyển dụng nào được tìm thấy.
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
              {{ $jobs->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('customJs')
  <script type="text/javascript">
    $("#searchForm").submit(function(e) {
      e.preventDefault();
      var url = '{{ route('account.myJobs') }}?';
      var keyword = $("#keyword").val();
      if (keyword != "") {
        url += '&keyword=' + encodeURIComponent(keyword);
      }
      window.location.href = url;
    });

    function confirmation(jobId) {
      Swal.fire({
        title: 'Bạn có chắc chắn muốn xóa?',
        text: 'Tin tuyển dụng này sẽ bị xóa vĩnh viễn khỏi hệ thống.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00b14f',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Đồng ý xóa',
        cancelButtonText: 'Hủy bỏ'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById(`remove-job-form-${jobId}`).submit();
        }
      });
    }
  </script>
@endsection
