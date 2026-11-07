@extends('front.layouts.app')

@section('main')
  <section class="tw-section tw-section-alt" style="padding-top: var(--sp-6);">
    <div class="container">
      
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: var(--text-sm);">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
          <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Công việc đã ứng tuyển</li>
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
                <h3 class="mb-1 border-0 pb-0">Hồ sơ đã ứng tuyển</h3>
                <p class="text-muted small mb-0" style="font-size: var(--text-xs);">
                  Theo dõi trạng thái duyệt hồ sơ của các công việc bạn đã nộp đơn.
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
                    <th scope="col" class="fw-semibold">Vị trí ứng tuyển</th>
                    <th scope="col" class="fw-semibold">Ngày nộp</th>
                    <th scope="col" class="fw-semibold">Tổng hồ sơ</th>
                    <th scope="col" class="fw-semibold">Trạng thái</th>
                    <th scope="col" class="text-end fw-semibold">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($jobApplications->isNotEmpty())
                    @foreach ($jobApplications as $jobApplication)
                      <form id="remove-jobApplication-form-{{ $jobApplication->id }}" action="{{ route('account.removeJobs') }}" method="POST">
                        @csrf
                        <tr>
                          <td style="display: none"><input type="hidden" name="id" value="{{ $jobApplication->id }}"></td>
                          <td>
                            <a href="{{ route('jobDetail', $jobApplication->job_id) }}" class="fw-bold text-dark text-decoration-none d-block">
                              {{ Str::words(strip_tags($jobApplication->job->title), 6) }}
                            </a>
                            <small class="text-muted">{{ $jobApplication->job->jobType->name }} · {{ $jobApplication->job->province ?? 'Toàn quốc' }}</small>
                          </td>
                          <td>{{ \Carbon\Carbon::parse($jobApplication->applied_date)->format('d/m/Y') }}</td>
                          <td>{{ $jobApplication->job->applications->count() }} người</td>
                          <td>
                            @if ($jobApplication->status == 1)
                              <span class="tw-job-badge tw-badge-type">Đã tiếp nhận</span>
                            @elseif ($jobApplication->status == 2)
                              <span class="tw-job-badge" style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;">Mời phỏng vấn</span>
                            @elseif ($jobApplication->status == 3)
                              <span class="tw-job-badge" style="background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0;">Trúng tuyển</span>
                            @elseif ($jobApplication->status == 4)
                              <span class="tw-job-badge" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca;">Chưa phù hợp</span>
                            @else
                              <span class="tw-job-badge tw-badge-salary">Đang chờ duyệt</span>
                            @endif
                          </td>                                                                                               
                          <td class="text-end">
                            <div class="dropdown d-inline-block">
                              <button class="tw-btn tw-btn-outline" style="padding: 3px 10px; font-size: var(--text-xs);" data-bs-toggle="dropdown">
                                Tuỳ chọn ▾
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end shadow-sm border" style="font-size: var(--text-sm);">
                                <li>
                                  <a class="dropdown-item" href="{{ route('jobDetail', $jobApplication->job_id) }}">
                                    Xem lại tin
                                  </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                  <button type="button" class="dropdown-item text-danger" onclick="confirmation({{ $jobApplication->id }})">
                                    Hủy ứng tuyển
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
                        Bạn chưa nộp hồ sơ vào công việc nào.
                      </td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
              {{ $jobApplications->links() }}
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
      var url = '{{ route('account.myJobApplication') }}?';
      var keyword = $("#keyword").val();
      if (keyword != "") {
        url += '&keyword=' + encodeURIComponent(keyword);
      }
      window.location.href = url;
    });

    function confirmation(jobApplicationId) {
      Swal.fire({
        title: 'Bạn có chắc chắn?',
        text: 'Đơn ứng tuyển của bạn sẽ bị hủy bỏ.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00b14f',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Không'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById(`remove-jobApplication-form-${jobApplicationId}`).submit();
        }
      });
    }
  </script>
@endsection
