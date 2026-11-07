@extends('front.layouts.app')

@section('main')
  <section class="tw-section tw-section-alt" style="padding-top: var(--sp-6);">
    <div class="container">
      
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: var(--text-sm);">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
          <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Tìm việc làm</li>
        </ol>
      </nav>

      <!-- Results Count & Sort bar -->
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
          <h1 class="h4 fw-bold mb-1" style="color: var(--c-text);">
            Tìm việc làm phù hợp
          </h1>
          <p class="text-muted mb-0 small">
            Tìm thấy <strong style="color: var(--c-brand);">{{ $jobsCount }}</strong> việc làm đang mở tuyển
          </p>
        </div>
        <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
          <label for="sort" class="text-muted small text-nowrap mb-0">Sắp xếp:</label>
          <select name="sort" id="sort" class="form-select form-select-sm" style="min-width: 140px; border-color: var(--c-border); font-size: var(--text-sm);">
            <option value="1" {{ Request::get('sort') == '1' || !Request::has('sort') ? 'selected' : '' }}>Mới nhất</option>
            <option value="0" {{ Request::get('sort') == '0' ? 'selected' : '' }}>Cũ nhất</option>
          </select>
        </div>
      </div>

      <div class="row g-4">
        <!-- FILTER SIDEBAR -->
        <div class="col-lg-3 col-md-4">
          <div class="tw-filter-card sticky-top" style="top: 76px; z-index: 10;">
            <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom">
              <h5 class="tw-filter-title">Bộ lọc tìm kiếm</h5>
              <a href="{{ route('jobs') }}" class="small text-decoration-none text-muted fw-medium" style="font-size: var(--text-xs);">
                Xóa tất cả
              </a>
            </div>

            <form action="{{ route('jobs') }}" method="GET" id="searchForm">
              <input type="hidden" name="sort" id="form_sort" value="{{ Request::get('sort', '1') }}">

              <!-- Từ khóa -->
              <div class="tw-filter-group">
                <label class="tw-filter-label" for="keyword">Từ khóa</label>
                <input value="{{ Request::get('keyword') }}" type="text" name="keyword" id="keyword"
                  placeholder="Vị trí, kỹ năng..." class="form-control form-control-sm" style="border-color: var(--c-border);">
              </div>

              <!-- Tỉnh / Thành phố -->
              <div class="tw-filter-group">
                <label class="tw-filter-label" for="province">Tỉnh / Thành phố</label>
                <select class="form-select form-select-sm" id="province" name="province" style="border-color: var(--c-border);">
                  <option value="">Tất cả địa điểm</option>
                  @if(!empty($provinces))
                    @php $currentProv = Request::get('province'); @endphp
                    @foreach ($provinces as $p)
                      <option value="{{ $p['full_name'] }}" {{ ($currentProv == $p['full_name'] || $currentProv == $p['name'] || $currentProv == $p['id']) ? 'selected' : '' }}>
                        {{ $p['full_name'] }}
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>

              <!-- Ngành nghề -->
              <div class="tw-filter-group">
                <label class="tw-filter-label" for="career">Ngành nghề</label>
                <select name="career" id="career" class="form-select form-select-sm" style="border-color: var(--c-border);">
                  <option value="">Tất cả ngành nghề</option>
                  @if ($careers->isNotEmpty())
                    @foreach ($careers as $career)
                      <option {{ Request::get('career') == $career->id ? 'selected' : '' }} value="{{ $career->id }}">
                        {{ $career->name }}
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>

              <!-- Mức lương -->
              <div class="tw-filter-group">
                <label class="tw-filter-label">Mức lương</label>
                @php $salary = Request::get('salary_range', ''); @endphp
                <div class="tw-radio-chips">
                  <div class="tw-radio-chip">
                    <input type="radio" name="salary_range" id="sal_all" value="" {{ $salary == '' ? 'checked' : '' }}>
                    <label for="sal_all">Tất cả mức lương</label>
                  </div>
                  <div class="tw-radio-chip">
                    <input type="radio" name="salary_range" id="sal_u10" value="under_10" {{ $salary == 'under_10' ? 'checked' : '' }}>
                    <label for="sal_u10">Dưới 10 triệu</label>
                  </div>
                  <div class="tw-radio-chip">
                    <input type="radio" name="salary_range" id="sal_1015" value="10_15" {{ $salary == '10_15' ? 'checked' : '' }}>
                    <label for="sal_1015">10 - 15 triệu</label>
                  </div>
                  <div class="tw-radio-chip">
                    <input type="radio" name="salary_range" id="sal_1520" value="15_20" {{ $salary == '15_20' ? 'checked' : '' }}>
                    <label for="sal_1520">15 - 20 triệu</label>
                  </div>
                  <div class="tw-radio-chip">
                    <input type="radio" name="salary_range" id="sal_2025" value="20_25" {{ $salary == '20_25' ? 'checked' : '' }}>
                    <label for="sal_2025">20 - 25 triệu</label>
                  </div>
                  <div class="tw-radio-chip">
                    <input type="radio" name="salary_range" id="sal_a25" value="above_25" {{ $salary == 'above_25' ? 'checked' : '' }}>
                    <label for="sal_a25">Trên 25 triệu</label>
                  </div>
                  <div class="tw-radio-chip">
                    <input type="radio" name="salary_range" id="sal_neg" value="negotiable" {{ $salary == 'negotiable' ? 'checked' : '' }}>
                    <label for="sal_neg">Thỏa thuận</label>
                  </div>
                </div>
              </div>

              <!-- Kinh nghiệm làm việc -->
              <div class="tw-filter-group">
                <label class="tw-filter-label" for="experience">Kinh nghiệm</label>
                @php $exp = Request::get('experience', ''); @endphp
                <select name="experience" id="experience" class="form-select form-select-sm" style="border-color: var(--c-border);">
                  <option value="" {{ $exp == '' ? 'selected' : '' }}>Tất cả kinh nghiệm</option>
                  <option value="none" {{ $exp == 'none' ? 'selected' : '' }}>Chưa có kinh nghiệm</option>
                  <option value="under_1" {{ $exp == 'under_1' ? 'selected' : '' }}>Dưới 1 năm</option>
                  <option value="1" {{ $exp == '1' ? 'selected' : '' }}>1 năm</option>
                  <option value="2" {{ $exp == '2' ? 'selected' : '' }}>2 năm</option>
                  <option value="3" {{ $exp == '3' ? 'selected' : '' }}>3 năm</option>
                  <option value="4" {{ $exp == '4' ? 'selected' : '' }}>4 năm</option>
                  <option value="5" {{ $exp == '5' ? 'selected' : '' }}>5 năm</option>
                  <option value="5_plus" {{ $exp == '5_plus' || $exp == '10_plus' ? 'selected' : '' }}>Trên 5 năm</option>
                </select>
              </div>

              <!-- Cấp bậc -->
              <div class="tw-filter-group">
                <label class="tw-filter-label" for="job_level">Cấp bậc</label>
                @php $level = Request::get('job_level', ''); @endphp
                <select name="job_level" id="job_level" class="form-select form-select-sm" style="border-color: var(--c-border);">
                  <option value="" {{ $level == '' ? 'selected' : '' }}>Tất cả cấp bậc</option>
                  <option value="Thực tập" {{ $level == 'Thực tập' ? 'selected' : '' }}>Thực tập sinh</option>
                  <option value="Nhân viên" {{ $level == 'Nhân viên' ? 'selected' : '' }}>Nhân viên / Chuyên viên</option>
                  <option value="Trưởng nhóm" {{ $level == 'Trưởng nhóm' ? 'selected' : '' }}>Trưởng nhóm / Leader</option>
                  <option value="Trưởng phòng" {{ $level == 'Trưởng phòng' ? 'selected' : '' }}>Trưởng / Phó phòng</option>
                  <option value="Giám đốc" {{ $level == 'Giám đốc' ? 'selected' : '' }}>Giám đốc / Quản lý</option>
                </select>
              </div>

              <!-- Hình thức làm việc -->
              <div class="tw-filter-group">
                <label class="tw-filter-label">Hình thức</label>
                @if ($jobTypes->isNotEmpty())
                  @foreach ($jobTypes as $jobType)
                    <div class="form-check small mb-1">
                      <input {{ in_array($jobType->id, $jobTypeArray) ? 'checked' : '' }} class="form-check-input"
                        name="job_type[]" type="checkbox" value="{{ $jobType->id }}" id="job-type-{{ $jobType->id }}">
                      <label class="form-check-label text-muted" for="job-type-{{ $jobType->id }}">
                        {{ $jobType->name }}
                      </label>
                    </div>
                  @endforeach
                @endif
              </div>

              <div class="pt-2">
                <button type="submit" class="tw-btn tw-btn-primary w-100 justify-content-center text-center">
                  Áp dụng bộ lọc
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- JOB LISTINGS -->
        <div class="col-lg-9 col-md-8">
          @if ($jobs->isNotEmpty())
            <div class="row g-3">
              @foreach ($jobs as $job)
                @php
                  $isSaved = in_array($job->id, $savedJobIds);
                  $companyLogo = !empty($job->user && $job->user->image)
                      ? asset('assets/user/profile_picture/thumb/' . $job->user->image)
                      : asset('assets/user/images/logo_web.jpg');
                  $jobUrl = (Auth::check() && Auth::user()->role === 'employer')
                      ? route('JobDetail_employer', $job->id)
                      : route('jobDetail', $job->id);
                @endphp
                <div class="col-xl-6 col-12 d-flex">
                  <div class="tw-job-card w-100">
                    <div class="tw-job-card-top">
                      <a href="{{ $jobUrl }}">
                        <img src="{{ $companyLogo }}" alt="{{ $job->company_name }}" class="tw-job-logo">
                      </a>

                      <div style="flex: 1; min-width: 0;">
                        <a href="{{ $jobUrl }}" class="tw-job-title text-decoration-none d-block text-truncate" title="{{ $job->title }}">
                          {{ $job->title }}
                        </a>
                        <div class="tw-job-company text-truncate">{{ $job->company_name }}</div>
                      </div>

                      <a href="javascript:void(0);"
                        class="tw-job-heart ms-auto"
                        onclick="toggleSaveJob({{ $job->id }}, this)"
                        title="{{ $isSaved ? 'Hủy lưu tin' : 'Lưu tin' }}">
                        <i class="fa {{ $isSaved ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                      </a>
                    </div>

                    <div class="tw-job-meta">
                      <span class="tw-job-badge tw-badge-salary">
                        {{ !empty($job->salary) ? $job->salary : 'Thỏa thuận' }}
                      </span>
                      @if (!empty($job->province))
                        <span class="tw-job-badge tw-badge-loc">{{ $job->province }}</span>
                      @elseif (!empty($job->wards))
                        <span class="tw-job-badge tw-badge-loc">{{ $job->wards }}</span>
                      @elseif (!empty($job->district))
                        <span class="tw-job-badge tw-badge-loc">{{ $job->district }}</span>
                      @endif

                      @if (!empty($job->experience))
                        <span class="tw-job-badge tw-badge-loc">
                          {{ is_numeric($job->experience) ? $job->experience . ' năm KN' : $job->experience }}
                        </span>
                      @endif

                      @if (!empty($job->jobType))
                        <span class="tw-job-badge tw-badge-type">{{ $job->jobType->name }}</span>
                      @endif
                    </div>

                    @if (!empty($job->keywords))
                      <div class="tw-job-tags">
                        @php $keywords = explode(',', $job->keywords); @endphp
                        @foreach ($keywords as $idx => $kw)
                          @if ($idx < 3 && trim($kw) != '')
                            <span class="tw-job-tag">{{ trim($kw) }}</span>
                          @endif
                        @endforeach
                      </div>
                    @endif

                    <div class="tw-job-footer d-flex justify-content-between align-items-center pt-2 border-top mt-auto">
                      <span class="small text-muted" style="font-size: var(--text-xs);">
                        @if ($job->expiration_date)
                          @php
                            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($job->expiration_date), false);
                          @endphp
                          @if ($daysLeft > 0)
                            Còn {{ $daysLeft }} ngày
                          @elseif ($daysLeft == 0)
                            <span class="text-danger">Hết hạn hôm nay</span>
                          @else
                            Đã hết hạn
                          @endif
                        @else
                          Đăng {{ \Carbon\Carbon::parse($job->created_at)->diffForHumans() }}
                        @endif
                      </span>

                      <a href="{{ $jobUrl }}" class="tw-btn tw-btn-outline" style="padding: 4px 12px; font-size: var(--text-xs);">
                        Xem chi tiết
                      </a>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
              {{ $jobs->links() }}
            </div>
          @else
            <!-- Empty state -->
            <div class="tw-content-card text-center py-5">
              <h4 class="fw-bold mb-2">Chưa tìm thấy công việc phù hợp</h4>
              <p class="text-muted mx-auto mb-4" style="max-width: 440px;">
                Chưa có việc làm nào khớp với bộ lọc hiện tại. Bạn vui lòng thử thay đổi từ khóa hoặc mở rộng tiêu chí.
              </p>
              <div>
                <a href="{{ route('jobs') }}" class="tw-btn tw-btn-primary">
                  Xem tất cả việc làm
                </a>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection

@section('customJs')
  <script>
    $("#searchForm").submit(function(e) {
      e.preventDefault();
      var url = '{{ route('jobs') }}?';

      var keyword = $("#keyword").val().trim();
      var province = $("#province").val();
      var career = $("#career").val();
      var experience = $("#experience").val();
      var jobLevel = $("#job_level").val();
      var salaryRange = $("input[name='salary_range']:checked").val();
      var sort = $("#sort").val();

      var checkedJobTypes = $("input:checkbox[name='job_type[]']:checked, input:checkbox[name='job_type']:checked").map(function() {
        return $(this).val();
      }).get();

      if (keyword !== "") {
        url += '&keyword=' + encodeURIComponent(keyword);
      }
      if (province && province !== "") {
        url += '&province=' + encodeURIComponent(province);
      }
      if (career !== "") {
        url += '&career=' + career;
      }
      if (experience !== "") {
        url += '&experience=' + experience;
      }
      if (jobLevel !== "") {
        url += '&job_level=' + encodeURIComponent(jobLevel);
      }
      if (salaryRange && salaryRange !== "") {
        url += '&salary_range=' + salaryRange;
      }
      if (checkedJobTypes.length > 0) {
        url += '&jobType=' + checkedJobTypes.join(',');
      }
      if (sort !== "") {
        url += '&sort=' + sort;
      }

      window.location.href = url;
    });

    $("#sort").change(function() {
      $("#form_sort").val($(this).val());
      $("#searchForm").submit();
    });

    function toggleSaveJob(jobId, element) {
      @if (!Auth::check())
        toastr.warning('Vui lòng đăng nhập để lưu công việc này!');
        setTimeout(function() {
          window.location.href = "{{ route('account.login') }}";
        }, 1200);
        return;
      @endif

      $.ajax({
        url: '{{ route('saveJob') }}',
        type: 'POST',
        data: {
          id: jobId,
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(response) {
          if (response.status === true) {
            if (response.action === 'saved') {
              $(element).find('i').removeClass('fa-heart-o').addClass('fa-heart text-danger');
              toastr.success(response.message);
            } else {
              $(element).find('i').removeClass('fa-heart text-danger').addClass('fa-heart-o');
              toastr.info(response.message);
            }
          } else {
            toastr.error(response.message || 'Có lỗi xảy ra!');
          }
        },
        error: function() {
          toastr.error('Không thể kết nối đến máy chủ.');
        }
      });
    }
  </script>
@endsection
