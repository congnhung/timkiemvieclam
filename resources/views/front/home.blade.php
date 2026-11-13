@extends('front.layouts.app')

@section('main')

@php
$getCareerIcon = function($name) {
  $lower = mb_strtolower($name, 'UTF-8');
  if (str_contains($lower, 'công nghệ') || str_contains($lower, 'it') || str_contains($lower, 'lập trình')) return 'fa-code';
  if (str_contains($lower, 'kinh doanh') || str_contains($lower, 'sales') || str_contains($lower, 'bán hàng')) return 'fa-line-chart';
  if (str_contains($lower, 'marketing') || str_contains($lower, 'quảng cáo') || str_contains($lower, 'truyền thông')) return 'fa-bullhorn';
  if (str_contains($lower, 'kế toán') || str_contains($lower, 'tài chính') || str_contains($lower, 'ngân hàng')) return 'fa-calculator';
  if (str_contains($lower, 'thiết kế') || str_contains($lower, 'đồ họa') || str_contains($lower, 'design')) return 'fa-paint-brush';
  if (str_contains($lower, 'giáo dục') || str_contains($lower, 'đào tạo') || str_contains($lower, 'giảng viên')) return 'fa-graduation-cap';
  if (str_contains($lower, 'y tế') || str_contains($lower, 'dược') || str_contains($lower, 'bác sĩ')) return 'fa-heartbeat';
  if (str_contains($lower, 'xây dựng') || str_contains($lower, 'kiến trúc')) return 'fa-building';
  if (str_contains($lower, 'nhân sự') || str_contains($lower, 'hr') || str_contains($lower, 'hành chính')) return 'fa-users';
  if (str_contains($lower, 'logistics') || str_contains($lower, 'vận chuyển') || str_contains($lower, 'kho vận')) return 'fa-truck';
  if (str_contains($lower, 'dịch vụ') || str_contains($lower, 'khách sạn') || str_contains($lower, 'nhà hàng')) return 'fa-cutlery';
  return 'fa-briefcase';
};
@endphp

{{-- ===== HERO ===== --}}
<section class="tw-hero">
  <div class="container">
    <div class="row align-items-center gy-5">

      <div class="col-lg-6 tw-hero-inner">
        @if(isset($banners) && $banners->isNotEmpty())
          <div id="heroBannerCarousel" class="carousel slide carousel-fade tw-hero-carousel" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-indicators">
              @foreach($banners as $index => $banner)
                <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
              @endforeach
            </div>
            <div class="carousel-inner">
              @foreach($banners as $index => $banner)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                  @if(!empty($banner->link))
                    <a href="{{ $banner->link }}" target="{{ str_starts_with($banner->link, 'http') ? '_blank' : '_self' }}" class="d-block position-relative text-decoration-none">
                  @else
                    <div class="position-relative">
                  @endif
                      <img src="{{ asset($banner->image) }}" class="tw-hero-banner-img" alt="{{ $banner->title }}">
                      <div class="carousel-caption">
                        <span class="badge bg-success mb-1" style="font-size: 11px; font-weight: 500;">
                          <i class="fa fa-bullhorn me-1"></i> Quảng cáo nhà tuyển dụng
                        </span>
                        <h4 class="text-white mb-0 fw-bold" style="font-size: 1.15rem; text-shadow: 0 2px 4px rgba(0,0,0,0.6);">{{ $banner->title }}</h4>
                      </div>
                  @if(!empty($banner->link))
                    </a>
                  @else
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="prev" style="width: 48px;">
              <span class="carousel-control-prev-icon p-2 rounded-circle bg-dark bg-opacity-50" aria-hidden="true" style="background-size: 55%;"></span>
              <span class="visually-hidden">Trước</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="next" style="width: 48px;">
              <span class="carousel-control-next-icon p-2 rounded-circle bg-dark bg-opacity-50" aria-hidden="true" style="background-size: 55%;"></span>
              <span class="visually-hidden">Sau</span>
            </button>
          </div>
        @else
          <h1>Tìm công việc <em>phù hợp</em><br>với bạn ngay hôm nay</h1>
          <p class="tw-hero-sub">Kết nối ứng viên với hàng nghìn nhà tuyển dụng uy tín. Tạo CV chuyên nghiệp và ứng tuyển nhanh chóng.</p>
        @endif

        <div class="tw-hero-stats mt-4">
          <div class="tw-stat-card">
            <div class="tw-stat-num">10.000+</div>
            <div class="tw-stat-label"><i class="fa fa-briefcase text-success"></i> Việc làm</div>
          </div>
          <div class="tw-stat-card">
            <div class="tw-stat-num">2.000+</div>
            <div class="tw-stat-label"><i class="fa fa-building text-success"></i> Doanh nghiệp</div>
          </div>
          <div class="tw-stat-card">
            <div class="tw-stat-num">50.000+</div>
            <div class="tw-stat-label"><i class="fa fa-users text-success"></i> Ứng viên</div>
          </div>
        </div>
      </div>

      {{-- Search Card --}}
      <div class="col-lg-5 offset-lg-1" style="position:relative;z-index:10;">
        <div class="tw-search-box">
          <div class="mb-3 pb-2 border-bottom">
            <h3 class="fw-bold text-dark mb-1 fs-6">Tìm việc làm phù hợp</h3>
            <small class="text-muted">Cập nhật hơn 1.000+ cơ hội mới mỗi ngày</small>
          </div>

          <form id="searchFormHome">
            <div class="tw-search-field">
              <label for="keyword">Từ khoá / Vị trí</label>
              <div class="tw-search-input-wrap">
                <i class="fa fa-search tw-input-icon"></i>
                <input type="text" name="keyword" id="keyword" placeholder="VD: PHP Developer, Marketing...">
              </div>
            </div>

            <div class="tw-search-field">
              <label for="province">Tỉnh / Thành phố</label>
              <div class="tw-search-input-wrap">
                <i class="fa fa-map-marker tw-input-icon"></i>
                <select id="province" name="province">
                  <option value="">Tất cả địa điểm</option>
                  @if(!empty($provinces))
                    @foreach ($provinces as $p)
                      <option value="{{ $p['full_name'] }}" {{ Request::get('province') == $p['full_name'] ? 'selected' : '' }}>
                        {{ $p['full_name'] }}
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>

            <div class="tw-search-field">
              <label for="career">Ngành nghề</label>
              <div class="tw-search-input-wrap">
                <i class="fa fa-briefcase tw-input-icon"></i>
                <select name="career" id="career">
                  <option value="">Tất cả ngành nghề</option>
                  @foreach ($newCareers as $career)
                    <option value="{{ $career->id }}">{{ $career->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <button type="submit" class="tw-search-btn-primary">
              <i class="fa fa-search me-1"></i> Tìm kiếm việc làm
            </button>

            <div class="tw-quick-tags">
              <span class="text-muted fw-semibold">Gợi ý:</span>
              <a href="{{ route('jobs', ['keyword' => 'PHP']) }}" class="tw-quick-tag">PHP</a>
              <a href="{{ route('jobs', ['keyword' => 'Marketing']) }}" class="tw-quick-tag">Marketing</a>
              <a href="{{ route('jobs', ['keyword' => 'Kế toán']) }}" class="tw-quick-tag">Kế toán</a>
              <a href="{{ route('jobs', ['keyword' => 'Kinh doanh']) }}" class="tw-quick-tag">Kinh doanh</a>
              <a href="{{ route('jobs', ['keyword' => 'Thực tập sinh']) }}" class="tw-quick-tag">Thực tập</a>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ===== VIỆC LÀM GẤP (Urgent Jobs) ===== --}}
@if($featureJobs->isNotEmpty())
<section class="tw-section tw-urgent-section">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
      <div class="tw-section-head mb-0">
        <h2 class="mb-1">Việc làm nổi bật</h2>
        <p class="mb-0 text-muted">Các vị trí có chế độ đãi ngộ tốt và nhiều ứng viên quan tâm</p>
      </div>
      <a href="{{ route('jobs') }}" class="tw-view-more-link d-none d-sm-inline-flex align-items-center gap-1 text-decoration-none">
        Xem tất cả <i class="fa fa-arrow-right"></i>
      </a>
    </div>

    {{-- Horizontal scrollable row on mobile, 2-col grid on desktop --}}
    <div class="tw-urgent-scroll-wrap">
      <div class="tw-urgent-row">
        @foreach ($featureJobs as $job)
          @php
            $detailRoute = (Auth::check() && Auth::user()->role === 'employer')
              ? route('JobDetail_employer', $job->id)
              : route('jobDetail', $job->id);
            $logoSrc = asset('assets/user/profile_picture/thumb/' . ($job->user->image ?? 'logo-page.jpg'));
          @endphp
          <div class="tw-urgent-card-wrap">
            <div class="tw-job-card-h">
              <img src="{{ $logoSrc }}" alt="{{ $job->company_name }}" class="tw-job-logo-h"
                   onerror="this.src='{{ asset('assets/user/images/avatar7.png') }}'">
              <div class="tw-job-card-h-body">
                <a href="{{ $detailRoute }}" class="tw-job-title tw-job-title-h" title="{{ $job->title }}">
                  {{ Str::words(strip_tags($job->title), 8) }}
                </a>
                <div class="tw-job-company mb-2">
                  <i class="fa fa-building-o me-1 text-muted"></i> {{ Str::words(strip_tags($job->company_name), 6) }}
                </div>
                <div class="tw-job-meta mb-0">
                  @if(!empty($job->province))
                    <span class="tw-job-badge tw-badge-loc">
                      <i class="fa fa-map-marker"></i> {{ $job->province }}
                    </span>
                  @endif
                  @if(Auth::check() && !is_null($job->salary))
                    <span class="tw-job-badge tw-badge-salary">
                      <i class="fa fa-money"></i> {{ $job->salary }}
                    </span>
                  @elseif(is_null($job->salary))
                    <span class="tw-job-badge tw-badge-salary">
                      <i class="fa fa-handshake-o"></i> Thỏa thuận
                    </span>
                  @endif
                  @if(!empty($job->jobType->name))
                    <span class="tw-job-badge tw-badge-type">
                      <i class="fa fa-briefcase"></i> {{ $job->jobType->name }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="tw-job-card-h-action">
                @if(Auth::check() && Auth::user()->role === 'user')
                  <a class="tw-job-heart" href="javascript:void(0)" onclick="saveJobHeart({{ $job->id }}, this)" title="Lưu việc làm">
                    <i class="fa {{ $job->is_saved ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                  </a>
                @endif
                <div class="tw-job-card-h-bottom">
                  <span class="tw-job-time">
                    <i class="fa fa-clock-o"></i> {{ $job->created_at ? $job->created_at->diffForHumans() : 'Mới' }}
                  </span>
                  <a href="{{ $detailRoute }}" class="tw-btn-detail">Ứng tuyển <i class="fa fa-arrow-right"></i></a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="text-center mt-4">
      <a href="{{ route('jobs') }}" class="tw-view-all">
        Xem tất cả việc làm <i class="fa fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>
@endif

{{-- ===== VIỆC LÀM MỚI NHẤT (horizontal card layout) ===== --}}
@if($latesJobs->isNotEmpty())
<section class="tw-section tw-section-alt">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
      <div class="tw-section-head mb-0">
        <h2 class="mb-1">Việc làm mới nhất</h2>
        <p class="mb-0 text-muted">Những cơ hội việc làm mới được đăng tuyển trong ngày</p>
      </div>
      <a href="{{ route('jobs') }}" class="tw-view-more-link d-none d-sm-inline-flex align-items-center gap-1 text-decoration-none">
        Xem tất cả <i class="fa fa-arrow-right"></i>
      </a>
    </div>

    <div class="row g-3">
      @foreach ($latesJobs as $job)
        @php
          $detailRoute = (Auth::check() && Auth::user()->role === 'employer')
            ? route('JobDetail_employer', $job->id)
            : route('jobDetail', $job->id);
          $logoSrc = asset('assets/user/profile_picture/thumb/' . ($job->user->image ?? 'logo-page.jpg'));
        @endphp
        <div class="col-12 col-md-6">
          <div class="tw-job-card-h tw-job-card-h-flat">
            <img src="{{ $logoSrc }}" alt="{{ $job->company_name }}" class="tw-job-logo-h"
                 onerror="this.src='{{ asset('assets/user/images/avatar7.png') }}'">
            <div class="tw-job-card-h-body">
              <a href="{{ $detailRoute }}" class="tw-job-title tw-job-title-h" title="{{ $job->title }}">
                {{ Str::words(strip_tags($job->title), 8) }}
              </a>
              <div class="tw-job-company mb-2">
                <i class="fa fa-building-o me-1 text-muted"></i> {{ Str::words(strip_tags($job->company_name), 6) }}
              </div>
              <div class="tw-job-meta mb-0">
                @if(!empty($job->province))
                  <span class="tw-job-badge tw-badge-loc">
                    <i class="fa fa-map-marker"></i> {{ $job->province }}
                  </span>
                @endif
                @if(Auth::check() && !is_null($job->salary))
                  <span class="tw-job-badge tw-badge-salary">
                    <i class="fa fa-money"></i> {{ $job->salary }}
                  </span>
                @elseif(is_null($job->salary))
                  <span class="tw-job-badge tw-badge-salary">
                    <i class="fa fa-handshake-o"></i> Thỏa thuận
                  </span>
                @endif
                @if(!empty($job->jobType->name))
                  <span class="tw-job-badge tw-badge-type">
                    <i class="fa fa-briefcase"></i> {{ $job->jobType->name }}
                  </span>
                @endif
              </div>
            </div>
            <div class="tw-job-card-h-action">
              @if(Auth::check() && Auth::user()->role === 'user')
                <a class="tw-job-heart" href="javascript:void(0)" onclick="saveJobHeart({{ $job->id }}, this)" title="Lưu việc làm">
                  <i class="fa {{ $job->is_saved ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                </a>
              @endif
              <div class="tw-job-card-h-bottom">
                <span class="tw-job-time">
                  <i class="fa fa-clock-o"></i> {{ $job->created_at ? $job->created_at->diffForHumans() : 'Mới' }}
                </span>
                <a href="{{ $detailRoute }}" class="tw-btn-detail">Ứng tuyển <i class="fa fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="text-center mt-4 pt-2">
      <a href="{{ route('jobs') }}" class="tw-view-all">
        Xem tất cả việc làm mới nhất <i class="fa fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>
@endif

{{-- ===== NGÀNH NGHỀ PHỔ BIẾN ===== --}}
@if($careers->isNotEmpty())
<section class="tw-section">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
      <div class="tw-section-head mb-0">
        <h2 class="mb-1">Top ngành nghề nổi bật</h2>
        <p class="mb-0 text-muted">Lựa chọn ngành nghề phù hợp với năng lực và mục tiêu sự nghiệp</p>
      </div>
      <a href="{{ route('jobs') }}" class="tw-view-more-link d-none d-sm-inline-flex align-items-center gap-1 text-decoration-none">
        Xem tất cả việc làm <i class="fa fa-arrow-right"></i>
      </a>
    </div>

    <div class="tw-career-grid">
      @foreach ($careers as $group)
        @foreach ($group as $career)
          <a href="{{ route('jobs') }}?career={{ $career->id }}" class="tw-career-chip">
            <div class="tw-career-chip-icon">
              <i class="fa {{ $getCareerIcon($career->name) }}"></i>
            </div>
            <div class="tw-career-chip-info">
              <span class="tw-career-chip-name" title="{{ $career->name }}">{{ $career->name }}</span>
              <span class="tw-career-chip-count">{{ number_format($career->jobs_count) }} việc làm</span>
            </div>
          </a>
        @endforeach
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ===== DOANH NGHIỆP NỔI BẬT ===== --}}
@if(isset($topEmployers) && $topEmployers->isNotEmpty())
<section class="tw-section tw-section-alt">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
      <div class="tw-section-head mb-0">
        <h2 class="mb-1">Công ty nổi bật</h2>
        <p class="mb-0 text-muted">Những doanh nghiệp hàng đầu đang tuyển dụng tích cực</p>
      </div>
    </div>

    <div class="tw-company-grid">
      @foreach ($topEmployers as $employer)
        @php
          $employerLogo = asset('assets/user/profile_picture/thumb/' . ($employer->image ?? 'logo-page.jpg'));
        @endphp
        <a href="{{ route('jobs', ['company' => $employer->company_name ?? $employer->name]) }}"
           class="tw-company-logo-item" title="{{ $employer->company_name ?? $employer->name }}">
          <div class="tw-company-logo-inner">
            <img src="{{ $employerLogo }}"
                 alt="{{ $employer->company_name ?? $employer->name }}"
                 onerror="this.src='{{ asset('assets/user/images/avatar7.png') }}'">
            <div class="tw-company-logo-label">{{ Str::limit($employer->company_name ?? $employer->name, 20) }}</div>
            <div class="tw-company-logo-count"><i class="fa fa-briefcase me-1"></i>{{ $employer->jobs_count }} việc làm</div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ===== CTA BANNER ===== --}}
<section class="tw-section pt-3 pb-5">
  <div class="container">
    <div class="tw-cta p-4 p-md-5">
      <div class="row align-items-center justify-content-center">
        <div class="col-lg-8 text-center">
          <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 rounded-pill px-3 py-1 mb-3 fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 11px;">
            Dành cho Nhà tuyển dụng & Doanh nghiệp
          </span>
          <h3 class="fw-bold text-white mb-2" style="font-size: clamp(22px, 3.2vw, 32px);">
            Bạn đang tìm kiếm nhân sự tài năng?
          </h3>
          <p class="text-white-50 fs-6 mb-4 mx-auto" style="max-width: 620px;">
            Tiếp cận mạng lưới hơn 50.000+ hồ sơ ứng viên chất lượng cao. Đăng tin tuyển dụng hoàn toàn miễn phí và quản lý hồ sơ ứng tuyển thông minh.
          </p>
          <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center mb-4">
            <span class="text-white-50 small"><i class="fa fa-check-circle text-success me-1"></i> Đăng tin miễn phí</span>
            <span class="text-white-50 small"><i class="fa fa-check-circle text-success me-1"></i> Quản lý CV thông minh</span>
            <span class="text-white-50 small"><i class="fa fa-check-circle text-success me-1"></i> Tiếp cận ứng viên chuẩn xác</span>
          </div>
          <div class="tw-cta-btns">
            <a href="{{ route('account.createJob') }}" class="tw-cta-btn-green shadow-sm">
              <i class="fa fa-plus-circle me-1"></i> Đăng tin tuyển dụng ngay
            </a>
            <a href="{{ route('cv.builder') }}" class="tw-cta-btn-outline">
              <i class="fa fa-file-text-o me-1"></i> Tạo CV xin việc
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@section('customJs')
<script>
document.getElementById('searchFormHome').addEventListener('submit', function(e) {
  e.preventDefault();
  let url = '{{ route("jobs") }}?';
  const keyword = document.getElementById('keyword').value.trim();
  const province = document.getElementById('province').value;
  const career = document.getElementById('career').value;
  if (keyword) url += '&keyword=' + encodeURIComponent(keyword);
  if (province) url += '&province=' + encodeURIComponent(province);
  if (career) url += '&career=' + career;
  window.location.href = url;
});

function saveJobHeart(id, element) {
  event.preventDefault();
  $.ajax({
    url: '{{ route("saveJob") }}', type: 'post',
    data: { id: id }, dataType: 'json',
    success: function(res) {
      if (res.status === false) { toastr.error(res.message); }
      else {
        if (res.action === 'saved') {
          $(element).find('i').removeClass('fa-heart-o').addClass('fa-heart text-danger');
          toastr.success('Đã thêm vào yêu thích');
        } else {
          $(element).find('i').removeClass('fa-heart text-danger').addClass('fa-heart-o');
          toastr.warning('Đã hủy yêu thích');
        }
      }
    }
  });
}
</script>
@endsection
