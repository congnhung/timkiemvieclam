@extends('front.layouts.app')

@section('main')
  <section class="tw-section tw-section-alt" style="padding-top: var(--sp-6);">
    <div class="container">
      
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: var(--text-sm);">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('account.myJobs') }}" class="text-decoration-none text-muted">Tin đã đăng</a></li>
          <li class="breadcrumb-item active text-dark fw-medium text-truncate" style="max-width: 320px;" aria-current="page">
            {{ $job->title }}
          </li>
        </ol>
      </nav>

      <!-- DETAIL HEADER CARD -->
      <div class="tw-detail-header-card">
        <div class="d-flex flex-column flex-md-row align-items-start gap-4">
          <div>
            @php
              $companyLogo = !empty($job->user && $job->user->image)
                  ? asset('assets/user/profile_picture/thumb/' . $job->user->image)
                  : asset('assets/user/images/logo_web.jpg');
            @endphp
            <img src="{{ $companyLogo }}" alt="{{ $job->company_name }}" class="tw-detail-logo">
          </div>

          <div class="flex-grow-1">
            <h1 class="tw-detail-title">{{ $job->title }}</h1>
            <div class="tw-detail-company">
              <span>{{ $job->company_name }}</span>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-3" style="font-size: var(--text-sm);">
              <span class="text-muted">
                Đăng ngày: {{ \Carbon\Carbon::parse($job->created_at)->format('d/m/Y') }}
              </span>
              @if ($job->expiration_date)
                @php
                  $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($job->expiration_date), false);
                @endphp
                <span class="text-muted">
                  Hạn nộp: <strong class="text-dark">{{ \Carbon\Carbon::parse($job->expiration_date)->format('d/m/Y') }}</strong>
                  @if ($daysLeft >= 0)
                    (Còn {{ $daysLeft }} ngày)
                  @else
                    <span class="text-danger">(Đã hết hạn)</span>
                  @endif
                </span>
              @endif
            </div>
          </div>

          <div class="d-flex gap-2 ms-md-auto mt-3 mt-md-0">
            <a href="{{ route('account.editJob', $job->id) }}" class="tw-btn tw-btn-outline">
              Chỉnh sửa tin
            </a>
            <a href="{{ route('jobDetail', $job->id) }}" target="_blank" class="tw-btn tw-btn-primary">
              Xem trang ứng viên
            </a>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <!-- LEFT: DESCRIPTION & APPLICANTS -->
        <div class="col-lg-8">
          <!-- 6 METRICS -->
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
              <div class="tw-metric-value">{{ !empty($job->experience) ? $job->experience . ' năm' : 'Không yêu cầu' }}</div>
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

          <!-- DESCRIPTION -->
          <div class="tw-content-card">
            <div class="mb-4">
              <h3>Mô tả công việc</h3>
              <div>{!! !empty($job->description) ? (strip_tags($job->description) != $job->description ? $job->description : nl2br(e($job->description))) : 'Chưa có thông tin' !!}</div>
            </div>

            <div class="mb-4">
              <h3>Trách nhiệm công việc</h3>
              <div>{!! !empty($job->responsibility) ? (strip_tags($job->responsibility) != $job->responsibility ? $job->responsibility : nl2br(e($job->responsibility))) : 'Chưa có thông tin' !!}</div>
            </div>

            <div class="mb-4">
              <h3>Yêu cầu ứng viên</h3>
              <div>{!! !empty($job->qualifications) ? (strip_tags($job->qualifications) != $job->qualifications ? $job->qualifications : nl2br(e($job->qualifications))) : 'Chưa có thông tin' !!}</div>
            </div>

            <div>
              <h3>Phúc lợi</h3>
              <div>{!! !empty($job->benefits) ? (strip_tags($job->benefits) != $job->benefits ? $job->benefits : nl2br(e($job->benefits))) : 'Chưa có thông tin' !!}</div>
            </div>
          </div>

          <!-- APPLICANTS LIST -->
          @if (Auth::user() && Auth::user()->id == $job->user_id)
            <div class="tw-content-card">
              <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h3 class="mb-0 border-0 pb-0">Danh sách ứng viên nộp hồ sơ</h3>
                <span class="tw-job-badge tw-badge-type">{{ $applications->count() }} hồ sơ</span>
              </div>

              @if ($applications->isNotEmpty())
                <div class="table-responsive">
                  <table class="table align-middle" style="font-size: var(--text-sm);">
                    <thead class="table-light">
                      <tr>
                        <th class="fw-semibold">Ứng viên</th>
                        <th class="fw-semibold">Liên hệ</th>
                        <th class="fw-semibold">Ngày nộp</th>
                        <th class="fw-semibold">CV</th>
                        <th class="fw-semibold">Trạng thái</th>
                        <th class="text-end fw-semibold">Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($applications as $application)
                        <tr>
                          <td>
                            <strong class="text-dark d-block">{{ $application->user->fullname }}</strong>
                          </td>
                          <td>
                            <div>{{ $application->user->email }}</div>
                            <small class="text-muted">{{ $application->user->mobile ?? 'Chưa có SĐT' }}</small>
                          </td>
                          <td>
                            {{ \Carbon\Carbon::parse($application->applied_date)->format('d/m/Y') }}
                          </td>
                          <td>
                            @if ($application->cv_type === 'online' || $application->resume_id)
                              <a href="{{ route('cv.preview', $application->resume_id) }}" target="_blank" class="tw-btn tw-btn-primary" style="padding: 3px 8px; font-size: var(--text-xs);" title="Xem CV Online trực tiếp">
                                <i class="fa fa-id-card-o me-1"></i> CV Online
                              </a>
                            @elseif (!empty($application->cv_path))
                              <div class="d-inline-flex gap-1">
                                <a href="{{ route('view-cv', $application->cv_path) }}" target="_blank" class="tw-btn tw-btn-primary" style="padding: 3px 8px; font-size: var(--text-xs);" title="Xem trực tiếp trên trình duyệt">
                                  <i class="fa fa-eye me-1"></i> Xem
                                </a>
                                <a href="{{ route('download-cv', $application->cv_path) }}" class="tw-btn tw-btn-outline" style="padding: 3px 8px; font-size: var(--text-xs);" title="Tải file về máy">
                                  <i class="fa fa-download"></i>
                                </a>
                              </div>
                            @else
                              <span class="text-muted small">Chưa có CV</span>
                            @endif
                          </td>
                          <td>
                            @if ($application->status == 1)
                              <span class="tw-job-badge tw-badge-type">Đã tiếp nhận</span>
                            @elseif ($application->status == 2)
                              <span class="tw-job-badge" style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;">Mời phỏng vấn</span>
                            @elseif ($application->status == 3)
                              <span class="tw-job-badge" style="background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0;">Trúng tuyển</span>
                            @elseif ($application->status == 4)
                              <span class="tw-job-badge" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca;">Chưa phù hợp</span>
                            @else
                              <span class="tw-job-badge tw-badge-salary">Đang chờ duyệt</span>
                            @endif
                          </td>
                          <td class="text-end">
                            <button type="button" class="tw-btn tw-btn-outline me-1 btn-open-chat" style="padding: 3px 10px; font-size: var(--text-xs); border-color: #00b14f; color: #00b14f;"
                              data-job-id="{{ $job->id }}" data-candidate-id="{{ $application->user_id }}" title="Nhắn tin với ứng viên này">
                              <i class="fa fa-commenting-o me-1"></i> Nhắn tin
                            </button>
                            <button type="button" class="tw-btn tw-btn-primary" style="padding: 3px 10px; font-size: var(--text-xs);"
                              onclick="showMessagePopup(event, {{ $application->id }})">
                              Xử lý
                            </button>
                          </td>
                        </tr>

                        <!-- Modal Phê duyệt & Gửi thông báo -->
                        <div class="modal fade" id="processApplicationModal{{ $application->id }}" tabindex="-1"
                          aria-labelledby="processApplicationModalLabel{{ $application->id }}" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="border-radius: var(--r-lg); border: 1px solid var(--c-border);">
                              <div class="modal-header border-bottom pb-3">
                                <h5 class="modal-title fw-bold" id="processApplicationModalLabel{{ $application->id }}">
                                  Xử lý hồ sơ: {{ $application->user->fullname }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body p-4">
                                <form action="{{ route('process.application', ['id' => $application->id]) }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="id" value="{{ $application->id }}">

                                  <div class="tw-form-group">
                                    <label for="approvalSelect{{ $application->id }}">Trạng thái xét duyệt hồ sơ</label>
                                    <select class="tw-input" id="approvalSelect{{ $application->id }}" name="approval_status">
                                      <option value="0" {{ $application->status == '0' ? 'selected' : '' }}>⏳ Đang chờ xét duyệt</option>
                                      <option value="1" {{ $application->status == '1' ? 'selected' : '' }}>📄 Đã tiếp nhận hồ sơ</option>
                                      <option value="2" {{ $application->status == '2' ? 'selected' : '' }}>📅 Mời phỏng vấn</option>
                                      <option value="3" {{ $application->status == '3' ? 'selected' : '' }}>🎉 Trúng tuyển</option>
                                      <option value="4" {{ $application->status == '4' ? 'selected' : '' }}>❌ Chưa phù hợp / Từ chối</option>
                                    </select>
                                  </div>

                                  <div class="tw-form-group">
                                    <label for="messageContent_{{ $application->id }}">Tin nhắn gửi ứng viên</label>
                                    <textarea class="tw-input" id="messageContent_{{ $application->id }}" name="message" rows="3" placeholder="Nhập lời nhắn hoặc hướng dẫn phỏng vấn..."></textarea>
                                  </div>

                                  <div class="d-flex justify-content-end gap-2 pt-2">
                                    <button type="button" class="tw-btn tw-btn-outline" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="tw-btn tw-btn-primary">Gửi thông báo</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <p class="text-muted mb-0 small">Chưa có ứng viên nào nộp hồ sơ cho vị trí này.</p>
              @endif
            </div>
          @endif
        </div>

        <!-- RIGHT: SUMMARY -->
        <div class="col-lg-4">
          <div class="tw-content-card">
            <h3 class="mb-3">Thông tin đăng tuyển</h3>
            <div class="d-flex flex-column gap-3" style="font-size: var(--text-sm);">
              <div>
                <span class="text-muted d-block" style="font-size: var(--text-xs); text-transform: uppercase;">Địa chỉ</span>
                <span class="fw-medium">
                  {{ $job->location_detail ?? ($job->province ?? 'Việt Nam') }}
                </span>
              </div>
              @if (!empty($job->company_website))
                <div>
                  <span class="text-muted d-block" style="font-size: var(--text-xs); text-transform: uppercase;">Website</span>
                  <a href="{{ $job->company_website }}" target="_blank" class="text-decoration-none" style="color: var(--c-brand);">
                    {{ $job->company_website }}
                  </a>
                </div>
              @endif
              <div>
                <span class="text-muted d-block" style="font-size: var(--text-xs); text-transform: uppercase;">Người phụ trách</span>
                <span class="fw-medium">{{ $job->user->fullname }} ({{ $job->user->email }})</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('customJs')
  <script>
    function showMessagePopup(event, applicationId) {
      event.preventDefault();
      var modalElement = document.getElementById('processApplicationModal' + applicationId);
      if (modalElement) {
        var modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        modal.show();
      }
    }
  </script>
@endsection
