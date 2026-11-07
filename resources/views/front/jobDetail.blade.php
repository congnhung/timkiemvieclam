@extends('front.layouts.app')

@section('main')
  <section class="tw-section tw-section-alt" style="padding-top: var(--sp-6);">
    <div class="container">
      
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: var(--text-sm);">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('jobs') }}" class="text-decoration-none text-muted">Việc làm</a></li>
          <li class="breadcrumb-item active text-dark fw-medium text-truncate" style="max-width: 320px;" aria-current="page">
            {{ $job->title }}
          </li>
        </ol>
      </nav>

      <!-- DETAIL HEADER CARD -->
      <div class="tw-detail-header-card">
        <div class="d-flex flex-column flex-md-row align-items-start gap-4">
          <!-- Company Logo -->
          <div>
            @php
              $companyLogo = !empty($job->user && $job->user->image)
                  ? asset('assets/user/profile_picture/thumb/' . $job->user->image)
                  : asset('assets/user/images/logo_web.jpg');
            @endphp
            <img src="{{ $companyLogo }}" alt="{{ $job->company_name }}" class="tw-detail-logo">
          </div>

          <!-- Job Info & Title -->
          <div class="flex-grow-1">
            <h1 class="tw-detail-title">{{ $job->title }}</h1>

            <div class="tw-detail-company">
              <span>{{ $job->company_name }}</span>
              @if (!empty($job->company_website))
                <a href="{{ $job->company_website }}" target="_blank" class="small text-muted ms-2 text-decoration-none" title="Website công ty">
                  [Website]
                </a>
              @endif
            </div>

            <div class="d-flex flex-wrap align-items-center gap-3" style="font-size: var(--text-sm);">
              @php
                $daysLeft = null;
                if ($job->expiration_date) {
                    $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($job->expiration_date), false);
                }
              @endphp

              <span class="text-muted">
                Hạn nộp hồ sơ: 
                <strong class="text-dark">{{ $job->expiration_date ? \Carbon\Carbon::parse($job->expiration_date)->format('d/m/Y') : 'Tuyển liên tục' }}</strong>
                @if ($daysLeft !== null)
                  @if ($daysLeft > 0)
                    (Còn {{ $daysLeft }} ngày)
                  @elseif ($daysLeft == 0)
                    <span class="text-danger fw-bold">(Hết hạn hôm nay)</span>
                  @else
                    <span class="text-danger">(Đã hết hạn)</span>
                  @endif
                @endif
              </span>

              <span class="text-muted">
                Đăng ngày: {{ \Carbon\Carbon::parse($job->created_at)->format('d/m/Y') }}
              </span>
            </div>
          </div>

          <!-- Top Actions -->
          <div class="d-flex flex-row flex-md-column gap-2 ms-md-auto mt-3 mt-md-0 w-100 w-md-auto" style="min-width: 180px;">
            @if (Auth::check() && Auth::user()->role === 'user')
              @if ($userHasApplied)
                <button class="tw-btn tw-btn-outline w-100 justify-content-center text-center" disabled>
                  Đã ứng tuyển
                </button>
              @else
                <button class="tw-btn tw-btn-primary w-100 justify-content-center text-center" data-bs-toggle="modal" data-bs-target="#applyJobModal">
                  Ứng tuyển ngay
                </button>
              @endif
              <button type="button" class="tw-btn tw-btn-outline w-100 justify-content-center text-center btn-open-chat"
                data-job-id="{{ $job->id }}" style="border-color: #00b14f; color: #00b14f; font-weight: 600;">
                <i class="fa fa-comments me-1"></i> Chat với NTD
              </button>
            @elseif (Auth::check() && Auth::user()->role === 'employer')
              <a href="{{ route('JobDetail_employer', $job->id) }}" class="tw-btn tw-btn-outline w-100 justify-content-center text-center">
                Quản lý ứng viên
              </a>
            @else
              <a href="{{ route('account.login') }}" class="tw-btn tw-btn-primary w-100 justify-content-center text-center">
                Đăng nhập để ứng tuyển
              </a>
              <a href="{{ route('account.login') }}" class="tw-btn tw-btn-outline w-100 justify-content-center text-center"
                style="border-color: #00b14f; color: #00b14f; font-weight: 600;">
                <i class="fa fa-comments me-1"></i> Chat với NTD
              </a>
            @endif

            <!-- Save Job Button -->
            <button type="button" class="tw-btn tw-btn-outline w-100 justify-content-center text-center"
              onclick="saveJobHeart({{ $job->id }}, this)">
              <i class="fa {{ $count == 1 ? 'fa-heart text-danger' : 'fa-heart-o' }} me-1"></i>
              <span class="save-text">{{ $count == 1 ? 'Đã lưu' : 'Lưu tin' }}</span>
            </button>
          </div>
        </div>
      </div>

      <!-- MAIN LAYOUT: LEFT CONTENT + RIGHT SIDEBAR -->
      <div class="row g-4">
        <!-- LEFT COLUMN: SPEC & CONTENT -->
        <div class="col-lg-8">
          <!-- 6 METRIC BOXES -->
          <div class="tw-metrics-grid">
            <div class="tw-metric-card">
              <div class="tw-metric-label">Mức lương</div>
              <div class="tw-metric-value" style="color: var(--c-brand);">{{ !empty($job->salary) ? $job->salary : 'Thỏa thuận' }}</div>
            </div>

            <div class="tw-metric-card">
              <div class="tw-metric-label">Địa điểm</div>
              <div class="tw-metric-value">{{ !empty($job->province) ? $job->province : 'Toàn quốc' }}</div>
            </div>

            <div class="tw-metric-card">
              <div class="tw-metric-label">Kinh nghiệm</div>
              <div class="tw-metric-value">
                @if (empty($job->experience) || $job->experience == '0')
                  Không yêu cầu
                @elseif (is_numeric($job->experience))
                  {{ $job->experience }} năm
                @else
                  {{ $job->experience }}
                @endif
              </div>
            </div>

            <div class="tw-metric-card">
              <div class="tw-metric-label">Cấp bậc</div>
              <div class="tw-metric-value">{{ !empty($job->job_level) ? $job->job_level : 'Nhân viên' }}</div>
            </div>

            <div class="tw-metric-card">
              <div class="tw-metric-label">Hình thức</div>
              <div class="tw-metric-value">{{ !empty($job->jobType) ? $job->jobType->name : 'Toàn thời gian' }}</div>
            </div>

            <div class="tw-metric-card">
              <div class="tw-metric-label">Số lượng tuyển</div>
              <div class="tw-metric-value">{{ !empty($job->vacancy) ? $job->vacancy . ' người' : 'Không giới hạn' }}</div>
            </div>
          </div>

          <!-- JOB DESCRIPTION CARD -->
          <div class="tw-content-card">
            <div class="mb-5">
              <h3>Mô tả công việc</h3>
              <div>
                @if (!empty($job->description))
                  {!! strip_tags($job->description) != $job->description ? $job->description : nl2br(e($job->description)) !!}
                @else
                  <p class="text-muted fst-italic">Chưa có thông tin mô tả chi tiết từ nhà tuyển dụng.</p>
                @endif
              </div>
            </div>

            <div class="mb-5">
              <h3>Yêu cầu ứng viên</h3>
              <div>
                @if (!empty($job->qualifications))
                  {!! strip_tags($job->qualifications) != $job->qualifications ? $job->qualifications : nl2br(e($job->qualifications)) !!}
                @elseif (!empty($job->responsibility))
                  {!! strip_tags($job->responsibility) != $job->responsibility ? $job->responsibility : nl2br(e($job->responsibility)) !!}
                @else
                  <p class="text-muted fst-italic">Trao đổi cụ thể hơn khi phỏng vấn.</p>
                @endif
              </div>
            </div>

            <div class="mb-5">
              <h3>Quyền lợi & Phúc lợi</h3>
              <div>
                @if (!empty($job->benefits))
                  {!! strip_tags($job->benefits) != $job->benefits ? $job->benefits : nl2br(e($job->benefits)) !!}
                @else
                  <p class="text-muted fst-italic">Chế độ lương thưởng, bảo hiểm theo quy định của Luật lao động và chính sách công ty.</p>
                @endif
              </div>
            </div>

            <div class="mb-5">
              <h3>Địa điểm làm việc</h3>
              <div>
                <p>
                  @if (!empty($job->location_detail))
                    {{ $job->location_detail }}
                    @if (!empty($job->wards) && !empty($job->province))
                      ({{ $job->wards }}, {{ $job->province }})
                    @elseif (!empty($job->district) && !empty($job->province))
                      ({{ $job->district }}, {{ $job->province }})
                    @elseif (!empty($job->province))
                      ({{ $job->province }})
                    @endif
                  @elseif (!empty($job->wards) && !empty($job->province))
                    {{ $job->wards }}, {{ $job->province }}
                  @elseif (!empty($job->district) && !empty($job->province))
                    {{ $job->district }}, {{ $job->province }}
                  @else
                    {{ $job->province ?? 'Địa chỉ trao đổi trực tiếp khi liên hệ' }}
                  @endif
                </p>
              </div>
            </div>

            @if (!empty($job->keywords))
              <div class="mb-4">
                <h3>Kỹ năng liên quan</h3>
                <div class="d-flex flex-wrap gap-2">
                  @php $keywords = explode(',', $job->keywords); @endphp
                  @foreach ($keywords as $kw)
                    @if (trim($kw) != '')
                      <a href="{{ route('jobs', ['keyword' => trim($kw)]) }}" class="tw-job-tag">
                        {{ trim($kw) }}
                      </a>
                    @endif
                  @endforeach
                </div>
              </div>
            @endif

            <!-- BOTTOM ACTION BANNER -->
            <div class="p-4 rounded-3 border mt-5 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 bg-light">
              <div>
                <h5 class="fw-bold mb-1">Ứng tuyển công việc này</h5>
                <p class="text-muted small mb-0">Nộp CV trước hạn chót để nhà tuyển dụng xem xét sớm nhất.</p>
              </div>
              <div>
                @if (Auth::check() && Auth::user()->role === 'user')
                  @if ($userHasApplied)
                    <button class="tw-btn tw-btn-outline" disabled>Đã nộp đơn</button>
                  @else
                    <button class="tw-btn tw-btn-primary" data-bs-toggle="modal" data-bs-target="#applyJobModal">
                      Nộp hồ sơ ngay
                    </button>
                  @endif
                @else
                  <a href="{{ route('account.login') }}" class="tw-btn tw-btn-primary">
                    Đăng nhập để nộp đơn
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN: SIDEBAR -->
        <div class="col-lg-4">
          <!-- 1. THÔNG TIN DOANH NGHIỆP -->
          <div class="tw-content-card">
            <div class="text-center pb-3 border-bottom mb-3">
              <img src="{{ $companyLogo }}" alt="{{ $job->company_name }}" class="tw-job-logo mb-2" style="width: 56px; height: 56px;">
              <h5 class="fw-bold mb-1" style="color: var(--c-text);">{{ $job->company_name }}</h5>
              <span class="text-muted small" style="font-size: var(--text-xs);">Nhà tuyển dụng xác thực</span>
            </div>

            <div class="d-flex flex-column gap-3 mb-4" style="font-size: var(--text-sm);">
              <div>
                <span class="text-muted d-block" style="font-size: var(--text-xs); text-transform: uppercase;">Địa chỉ</span>
                <span class="fw-medium">
                  {{ $job->location_detail ?? ($job->province ?? 'Việt Nam') }}
                </span>
              </div>

              @if (!empty($job->company_website))
                <div>
                  <span class="text-muted d-block" style="font-size: var(--text-xs); text-transform: uppercase;">Website</span>
                  <a href="{{ $job->company_website }}" target="_blank" class="text-decoration-none fw-medium" style="color: var(--c-brand);">
                    {{ $job->company_website }}
                  </a>
                </div>
              @endif

              <div>
                <span class="text-muted d-block" style="font-size: var(--text-xs); text-transform: uppercase;">Email</span>
                <span class="fw-medium">{{ $job->user->email ?? 'Chưa công khai' }}</span>
              </div>
            </div>

            <div class="d-flex flex-column gap-2">
              @if (Auth::check() && Auth::user()->role === 'user')
                <button type="button" class="tw-btn tw-btn-primary w-100 text-center btn-open-chat"
                  data-job-id="{{ $job->id }}">
                  <i class="fa fa-comments me-1"></i> Nhắn tin với Nhà tuyển dụng
                </button>
              @elseif (!Auth::check())
                <a href="{{ route('account.login') }}" class="tw-btn tw-btn-primary w-100 text-center">
                  <i class="fa fa-comments me-1"></i> Nhắn tin với Nhà tuyển dụng
                </a>
              @endif

              <a href="{{ route('jobs', ['keyword' => $job->company_name]) }}" class="tw-btn tw-btn-outline w-100 text-center">
                Xem tất cả việc làm của công ty
              </a>
            </div>
          </div>

          <!-- 2. BANNER TẠO CV ONLINE -->
          <div class="tw-content-card text-center" style="background: var(--c-brand-light); border-color: rgba(0,177,79,0.2);">
            <h5 class="fw-bold mb-2">Chưa có CV chuyên nghiệp?</h5>
            <p class="text-muted small mb-4">Tạo CV online miễn phí theo chuẩn hiện đại và tải PDF ngay lập tức.</p>
            <a href="{{ route('cv.builder') }}" class="tw-btn tw-btn-primary w-100 text-center">
              Tạo CV ngay
            </a>
          </div>

          <!-- 3. VIỆC LÀM TƯƠNG TỰ -->
          @if (!empty($relatedJobs) && $relatedJobs->isNotEmpty())
            <div class="tw-content-card">
              <h6 class="fw-bold pb-2 mb-3 border-bottom">Việc làm liên quan</h6>
              <div class="d-flex flex-column gap-3">
                @foreach ($relatedJobs as $rJob)
                  @php
                    $rLogo = !empty($rJob->user && $rJob->user->image)
                        ? asset('assets/user/profile_picture/thumb/' . $rJob->user->image)
                        : asset('assets/user/images/logo_web.jpg');
                  @endphp
                  <div class="d-flex align-items-start gap-2 pb-2 border-bottom">
                    <img src="{{ $rLogo }}" alt="{{ $rJob->company_name }}" class="tw-job-logo" style="width: 40px; height: 40px;">
                    <div class="flex-grow-1 overflow-hidden">
                      <a href="{{ route('jobDetail', $rJob->id) }}"
                        class="text-decoration-none fw-medium text-dark d-block text-truncate"
                        style="font-size: var(--text-sm);"
                        title="{{ $rJob->title }}">
                        {{ $rJob->title }}
                      </a>
                      <span class="text-muted d-block text-truncate" style="font-size: var(--text-xs);">{{ $rJob->company_name }}</span>
                      <div class="d-flex gap-2 mt-1">
                        <span class="tw-job-badge tw-badge-salary" style="font-size: 10px;">
                          {{ !empty($rJob->salary) ? $rJob->salary : 'Thỏa thuận' }}
                        </span>
                        <span class="tw-job-badge tw-badge-loc" style="font-size: 10px;">
                          {{ $rJob->province ?? 'Toàn quốc' }}
                        </span>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>

  <!-- APPLY JOB MODAL -->
  @if (Auth::check() && Auth::user()->role === 'user')
    <div class="modal fade" id="applyJobModal" tabindex="-1" aria-labelledby="applyJobModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--r-lg); border: 1px solid var(--c-border); box-shadow: var(--shadow-lg);">
          <div class="modal-header border-bottom pb-3">
            <h5 class="modal-title fw-bold" id="applyJobModalLabel">
              Ứng tuyển: <span style="color: var(--c-brand);">{{ $job->title }}</span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body p-4">
            <form id="applyJobForm" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="{{ $job->id }}">
              @csrf

              <div class="tw-form-group">
                <label for="name">Họ và tên</label>
                <input type="text" class="tw-input" id="name" name="name"
                  value="{{ Auth::user()->fullname }}" readonly style="background-color: var(--c-gray-100);">
              </div>

              <div class="tw-form-group">
                <label for="email">Email liên hệ</label>
                <input type="email" class="tw-input" id="email" name="email"
                  value="{{ Auth::user()->email }}" readonly style="background-color: var(--c-gray-100);">
              </div>

              <div class="tw-form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" class="tw-input" id="phone" name="phone"
                  value="{{ Auth::user()->mobile ?? 'Chưa cập nhật' }}" readonly style="background-color: var(--c-gray-100);">
              </div>

              <!-- CHỌN PHƯƠNG THỨC NỘP CV -->
              <div class="tw-form-group my-3 p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <label class="fw-bold d-block mb-2 text-dark" style="font-size: 13px;">Phương thức nộp hồ sơ <span class="text-danger">*</span></label>
                <div class="d-flex gap-4">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="cv_type" id="cv_type_online" value="online" 
                      {{ ($userResumes && $userResumes->isNotEmpty()) ? 'checked' : '' }}
                      onchange="toggleCvType('online')">
                    <label class="form-check-label fw-semibold text-dark" for="cv_type_online" style="font-size: 13px; cursor: pointer;">
                      <i class="fa fa-id-card-o text-success me-1"></i> Sử dụng CV Online
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="cv_type" id="cv_type_upload" value="upload" 
                      {{ (!$userResumes || $userResumes->isEmpty()) ? 'checked' : '' }}
                      onchange="toggleCvType('upload')">
                    <label class="form-check-label fw-semibold text-dark" for="cv_type_upload" style="font-size: 13px; cursor: pointer;">
                      <i class="fa fa-upload text-primary me-1"></i> Tải file từ máy tính
                    </label>
                  </div>
                </div>
              </div>

              <!-- KHỐI CHỌN CV ONLINE -->
              <div id="section_cv_online" style="{{ ($userResumes && $userResumes->isNotEmpty()) ? '' : 'display: none;' }}">
                @if ($userResumes && $userResumes->isNotEmpty())
                  <div class="tw-form-group mb-3">
                    <label for="resume_id" class="small fw-semibold mb-1">Chọn CV đã tạo trên hệ thống:</label>
                    <select class="form-select" id="resume_id" name="resume_id" style="font-size: 13px;">
                      @foreach ($userResumes as $resume)
                        <option value="{{ $resume->id }}" data-preview-url="{{ route('cv.preview', $resume->id) }}">
                          {{ $resume->title ?? 'CV Chuyên Nghiệp' }} (Cập nhật: {{ $resume->updated_at->format('d/m/Y') }})
                        </option>
                      @endforeach
                    </select>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                      <a id="btnPreviewSelectedCv" href="{{ route('cv.preview', $userResumes->first()->id) }}" target="_blank" class="small text-decoration-none" style="color: #00b14f; font-weight: 600;">
                        <i class="fa fa-eye me-1"></i> Xem trước CV này
                      </a>
                      <a href="{{ route('cv.builder') }}" target="_blank" class="small text-muted text-decoration-none">
                        <i class="fa fa-plus me-1"></i> Tạo CV mới
                      </a>
                    </div>
                  </div>
                @else
                  <div class="p-3 bg-light rounded text-center small text-muted mb-3 border">
                    Bạn chưa có CV Online nào trên hệ thống.<br>
                    <a href="{{ route('cv.builder') }}" target="_blank" style="color: #00b14f; font-weight: 600;">Tạo CV Online ngay</a> hoặc chuyển sang tab "Tải file từ máy tính".
                  </div>
                @endif
              </div>

              <!-- KHỐI TẢI FILE CV -->
              <div id="section_cv_upload" style="{{ (!$userResumes || $userResumes->isEmpty()) ? '' : 'display: none;' }}">
                <div class="tw-form-group mb-3">
                  <label for="cv" class="small fw-semibold mb-1">
                    Tải lên file CV <span class="text-danger">* (.pdf, .doc, .docx)</span>
                  </label>
                  <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,.doc,.docx" style="font-size: 13px;">
                  <div class="invalid-feedback" id="cv-error"></div>
                  <div class="form-text small mt-1" style="font-size: var(--text-xs);">
                    Khuyên dùng file PDF dung lượng dưới 10MB.
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class="modal-footer border-top pt-3">
            <button type="button" class="tw-btn tw-btn-outline" data-bs-dismiss="modal">Đóng</button>
            <button type="button" class="tw-btn tw-btn-primary" onclick="submitApplication({{ $job->id }})">
              Nộp hồ sơ
            </button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Loading overlay -->
  <div id="loading-overlay"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255, 255, 255, 0.85); z-index:9999; text-align:center;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
      <div class="spinner-border text-success" role="status" style="width: 2.5rem; height: 2.5rem;">
        <span class="visually-hidden">Đang xử lý...</span>
      </div>
      <p class="mt-3 fw-medium text-dark" style="font-size: var(--text-sm);">Đang nộp hồ sơ của bạn, vui lòng đợi giây lát...</p>
    </div>
  </div>
@endsection

@section('customJs')
  <script type="text/javascript">
    function toggleCvType(type) {
      if (type === 'online') {
        $('#section_cv_online').slideDown(150);
        $('#section_cv_upload').slideUp(150);
      } else {
        $('#section_cv_online').slideUp(150);
        $('#section_cv_upload').slideDown(150);
      }
    }

    $(document).ready(function() {
      $('#resume_id').on('change', function() {
        var url = $(this).find('option:selected').data('preview-url');
        if (url) {
          $('#btnPreviewSelectedCv').attr('href', url);
        }
      });
    });

    function submitApplication(id) {
      var cvType = $('input[name="cv_type"]:checked').val() || 'upload';

      if (cvType === 'upload') {
        var cvFile = $('#cv')[0].files[0];
        if (!cvFile) {
          $('#cv').addClass('is-invalid');
          $('#cv-error').text('Vui lòng chọn file CV từ máy tính để ứng tuyển.');
          return;
        } else {
          $('#cv').removeClass('is-invalid');
          $('#cv-error').text('');
        }
      } else {
        var resumeId = $('#resume_id').val();
        if (!resumeId) {
          toastr.warning('Vui lòng chọn một CV Online hoặc tạo CV mới trước khi nộp.');
          return;
        }
      }

      var formData = new FormData($('#applyJobForm')[0]);
      formData.append('id', id);
      formData.append('cv_type', cvType);

      $('#loading-overlay').show();

      $.ajax({
        url: '{{ route('applyJob') }}',
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data: formData,
        success: function(response) {
          $('#loading-overlay').hide();
          if (response.status === true) {
            toastr.success(response.message || 'Nộp đơn thành công!');
            $('#applyJobModal').modal('hide');
            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            toastr.error(response.message || 'Có lỗi xảy ra khi nộp hồ sơ.');
            if (response.errors) {
              toastr.error(response.errors);
            }
          }
        },
        error: function(xhr) {
          $('#loading-overlay').hide();
          toastr.error('Có lỗi xảy ra trong quá trình nộp hồ sơ. Vui lòng thử lại!');
        }
      });
    }

    function saveJobHeart(id, element) {
      @if (!Auth::check())
        toastr.warning('Vui lòng đăng nhập để lưu công việc này!');
        setTimeout(function() {
          window.location.href = "{{ route('account.login') }}";
        }, 1200);
        return;
      @endif

      $.ajax({
        url: '{{ route('saveJob') }}',
        type: 'post',
        data: {
          id: id,
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(response) {
          if (response.status === false) {
            toastr.error(response.message);
          } else {
            if (response.action === 'saved') {
              $(element).find('i').removeClass('fa-heart-o').addClass('fa-heart text-danger');
              $(element).find('.save-text').text('Đã lưu');
              toastr.success(response.message || 'Đã thêm vào mục yêu thích');
            } else {
              $(element).find('i').removeClass('fa-heart text-danger').addClass('fa-heart-o');
              $(element).find('.save-text').text('Lưu tin');
              toastr.info(response.message || 'Đã hủy lưu việc làm');
            }
          }
        },
        error: function() {
          toastr.error("Có lỗi xảy ra, vui lòng thử lại.");
        }
      });
    }
  </script>
@endsection
