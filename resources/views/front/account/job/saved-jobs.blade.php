@extends('front.layouts.app')

@section('main')
  <section class="tw-section tw-section-alt" style="padding-top: var(--sp-6);">
    <div class="container">
      
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: var(--text-sm);">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
          <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Công việc đã lưu</li>
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
                <h3 class="mb-1 border-0 pb-0">Việc làm đã lưu</h3>
                <p class="text-muted small mb-0" style="font-size: var(--text-xs);">
                  Danh sách những công việc bạn đã quan tâm và lưu lại để ứng tuyển.
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
                    <th scope="col" class="fw-semibold">Vị trí việc làm</th>
                    <th scope="col" class="fw-semibold">Ứng viên đã nộp</th>
                    <th scope="col" class="fw-semibold">Trạng thái tin</th>
                    <th scope="col" class="text-end fw-semibold">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($savedJobs->isNotEmpty())
                    @foreach ($savedJobs as $savedJob)
                      <form id="remove-job-form-{{ $savedJob->id }}" action="{{ route('account.removeSavedJob') }}" method="POST">
                        @csrf 
                        <tr id="saved-job-{{ $savedJob->id }}">
                          <td style="display: none"><input type="hidden" name="id" value="{{ $savedJob->id }}"></td>
                          <td>
                            <a href="{{ route('jobDetail', $savedJob->job_id) }}" class="fw-bold text-dark text-decoration-none d-block">
                              {{ Str::words(strip_tags($savedJob->job->title), 6) }}
                            </a>
                            <small class="text-muted">{{ $savedJob->job->jobType->name }} · {{ $savedJob->job->province ?? 'Toàn quốc' }}</small>
                          </td>
                          <td>{{ $savedJob->job->applications->count() }} người</td>
                          <td>
                            @if ($savedJob->job->status == 1)
                              <span class="tw-job-badge tw-badge-type">Đang tuyển</span>
                            @elseif ($savedJob->job->status == 2)
                              <span class="tw-job-badge tw-badge-salary">Đang xử lý</span>
                            @else
                              <span class="tw-job-badge tw-badge-loc">Hết hạn</span>
                            @endif
                          </td>
                          <td class="text-end">
                            <div class="dropdown d-inline-block">
                              <button class="tw-btn tw-btn-outline" style="padding: 3px 10px; font-size: var(--text-xs);" data-bs-toggle="dropdown">
                                Tuỳ chọn ▾
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end shadow-sm border" style="font-size: var(--text-sm);">
                                <li>
                                  <a class="dropdown-item" href="{{ route('jobDetail', $savedJob->job_id) }}">
                                    Xem chi tiết
                                  </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                  <button type="button" class="dropdown-item text-danger" onclick="confirmation({{ $savedJob->id }})">
                                    Bỏ lưu tin
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
                      <td colspan="4" class="text-center py-4 text-muted">
                        Bạn chưa lưu công việc nào.
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
              {{ $savedJobs->links() }}
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
      var url = '{{ route('account.savedJobs') }}?';
      var keyword = $("#keyword").val();
      if (keyword != "") {
        url += '&keyword=' + encodeURIComponent(keyword);
      }
      window.location.href = url;
    });

    function confirmation(savedJobId) {
      Swal.fire({
        title: 'Bỏ lưu việc làm này?',
        text: 'Công việc sẽ được gỡ khỏi danh sách yêu thích của bạn.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00b14f',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Không'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById(`remove-job-form-${savedJobId}`).submit();
        }
      });
    }
  </script>
@endsection
