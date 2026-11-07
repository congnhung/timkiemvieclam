@extends('front.layouts.app')

@section('main')

@php
$careerIcons = ['Công nghệ'=>'fa-code','Marketing'=>'fa-bullhorn','Kinh doanh'=>'fa-briefcase',
  'Kế toán'=>'fa-calculator','Thiết kế'=>'fa-paint-brush','Giáo dục'=>'fa-graduation-cap',
  'Y tế'=>'fa-heartbeat','Xây dựng'=>'fa-building','default'=>'fa-tag'];
@endphp

{{-- ===== HERO ===== --}}
<section class="tw-hero">
  <div class="container">
    <div class="row align-items-center gy-5">

      <div class="col-lg-6 tw-hero-inner">
        @if(isset($banners) && $banners->isNotEmpty())
          <div id="heroBannerCarousel" class="carousel slide carousel-fade tw-hero-carousel" data-bs-ride="carousel" data-bs-interval="4000">
            <!-- Indicators -->
            <div class="carousel-indicators">
              @foreach($banners as $index => $banner)
                <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
              @endforeach
            </div>

            <!-- Slides -->
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

            <!-- Controls -->
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
          <span class="tw-hero-eyebrow">Nền tảng tuyển dụng Việt Nam</span>
          <h1>Tìm công việc <em>phù hợp</em><br>với bạn ngay hôm nay</h1>
          <p class="tw-hero-sub">Kết nối ứng viên với hàng nghìn nhà tuyển dụng uy tín. Tạo CV chuyên nghiệp và ứng tuyển nhanh chóng.</p>
        @endif

        <div class="tw-hero-stats mt-4">
          <div>
            <div class="tw-stat-num">10.000+</div>
            <div class="tw-stat-label">Việc làm</div>
          </div>
          <div>
            <div class="tw-stat-num">2.000+</div>
            <div class="tw-stat-label">Doanh nghiệp</div>
          </div>
          <div>
            <div class="tw-stat-num">50.000+</div>
            <div class="tw-stat-label">Ứng viên</div>
          </div>
        </div>
      </div>

      <div class="col-lg-5 offset-lg-1" style="position:relative;z-index:10;">
        <div class="tw-search-box">
          <div class="tw-search-box-title">Tìm việc làm phù hợp</div>
          <form id="searchFormHome">
            <div class="tw-search-field">
              <label for="keyword">Từ khoá / Vị trí</label>
              <input type="text" name="keyword" id="keyword" placeholder="VD: PHP Developer, Marketing...">
            </div>
            <div class="tw-search-field">
              <label for="province">Tỉnh / Thành phố</label>
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
            <div class="tw-search-field">
              <label for="career">Ngành nghề</label>
              <select name="career" id="career">
                <option value="">Tất cả ngành nghề</option>
                @foreach ($newCareers as $career)
                  <option value="{{ $career->id }}">{{ $career->name }}</option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="tw-search-btn">Tìm kiếm việc làm</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ===== NGÀNH NGHỀ PHỔ BIẾN ===== --}}
@if($careers->isNotEmpty())
<section class="tw-section">
  <div class="container">
    <div class="tw-section-head">
      <h2>Ngành nghề phổ biến</h2>
      <p>Chọn lĩnh vực phù hợp với bạn</p>
    </div>
    <div class="tw-career-grid">
      @foreach ($careers as $group)
        @foreach ($group as $career)
          <a href="{{ route('jobs') }}?career={{ $career->id }}" class="tw-career-chip">
            <span class="tw-career-chip-name">{{ $career->name }}</span>
            <span class="tw-career-chip-count">{{ $career->jobs_count }} việc</span>
          </a>
        @endforeach
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ===== VIỆC LÀM NỔI BẬT ===== --}}
@if($featureJobs->isNotEmpty())
<section class="tw-section tw-section-alt">
  <div class="container">
    <div class="tw-section-head">
      <h2>Việc làm nổi bật</h2>
      <p>Các vị trí đang được nhiều ứng viên quan tâm</p>
    </div>
    <div class="row g-3">
      @foreach ($featureJobs as $job)
        @php
          $detailRoute = (Auth::check() && Auth::user()->role === 'employer')
            ? route('JobDetail_employer', $job->id)
            : route('jobDetail', $job->id);
          $logoSrc = asset('assets/user/profile_picture/thumb/' . ($job->user->image ?? 'logo-page.jpg'));
        @endphp
        <div class="col-md-6 col-lg-4 d-flex">
          <div class="tw-job-card w-100">
            <div class="tw-job-card-top">
              <img src="{{ $logoSrc }}" alt="{{ $job->company_name }}" class="tw-job-logo">
              <div style="flex:1;min-width:0;">
                <div class="tw-job-title">{{ Str::words(strip_tags($job->title), 8) }}</div>
                <div class="tw-job-company">{{ Str::words(strip_tags($job->company_name), 6) }}</div>
              </div>
              @if(Auth::check() && Auth::user()->role === 'user')
                <a class="tw-job-heart" href="javascript:void(0)" onclick="saveJobHeart({{ $job->id }}, this)">
                  <i class="fa {{ $job->is_saved ? 'fa-heart' : 'fa-heart-o' }}"></i>
                </a>
              @endif
            </div>
            <div class="tw-job-meta">
              @if(!empty($job->province))
                <span class="tw-job-badge tw-badge-loc">{{ $job->province }}</span>
              @endif
              @if(!empty($job->jobType->name))
                <span class="tw-job-badge tw-badge-type">{{ $job->jobType->name }}</span>
              @endif
              @if(Auth::check() && !is_null($job->salary))
                <span class="tw-job-badge tw-badge-salary">{{ $job->salary }}</span>
              @elseif(is_null($job->salary))
                <span class="tw-job-badge tw-badge-salary">Thỏa thuận</span>
              @else
                <span class="tw-job-badge tw-badge-loc">Đăng nhập xem lương</span>
              @endif
            </div>
            @if(!empty($job->keywords))
              <div class="tw-job-tags">
                @foreach(array_slice(explode(',', $job->keywords), 0, 3) as $kw)
                  <a href="{{ route('jobs', ['keyword' => trim($kw)]) }}" class="tw-job-tag">{{ trim($kw) }}</a>
                @endforeach
              </div>
            @endif
            <div class="tw-job-footer">
              <a href="{{ $detailRoute }}" class="tw-btn-detail">Xem chi tiết</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-4">
      <a href="{{ route('jobs') }}" class="tw-view-all">Xem tất cả việc làm</a>
    </div>
  </div>
</section>
@endif

{{-- ===== VIỆC LÀM MỚI NHẤT ===== --}}
@if($latesJobs->isNotEmpty())
<section class="tw-section">
  <div class="container">
    <div class="tw-section-head">
      <h2>Việc làm mới nhất</h2>
      <p>Cập nhật liên tục mỗi ngày</p>
    </div>
    <div class="row g-3">
      @foreach ($latesJobs as $job)
        @php
          $detailRoute = (Auth::check() && Auth::user()->role === 'employer')
            ? route('JobDetail_employer', $job->id)
            : route('jobDetail', $job->id);
          $logoSrc = asset('assets/user/profile_picture/thumb/' . ($job->user->image ?? 'logo-page.jpg'));
        @endphp
        <div class="col-md-6 col-lg-4 d-flex">
          <div class="tw-job-card w-100">
            <div class="tw-job-card-top">
              <img src="{{ $logoSrc }}" alt="{{ $job->company_name }}" class="tw-job-logo">
              <div style="flex:1;min-width:0;">
                <div class="tw-job-title">{{ Str::words(strip_tags($job->title), 8) }}</div>
                <div class="tw-job-company">{{ Str::words(strip_tags($job->company_name), 6) }}</div>
              </div>
              @if(Auth::check() && Auth::user()->role === 'user')
                <a class="tw-job-heart" href="javascript:void(0)" onclick="saveJobHeart({{ $job->id }}, this)">
                  <i class="fa {{ $job->is_saved ? 'fa-heart' : 'fa-heart-o' }}"></i>
                </a>
              @endif
            </div>
            <div class="tw-job-meta">
              @if(!empty($job->province))
                <span class="tw-job-badge tw-badge-loc">{{ $job->province }}</span>
              @endif
              @if(!empty($job->jobType->name))
                <span class="tw-job-badge tw-badge-type">{{ $job->jobType->name }}</span>
              @endif
              @if(Auth::check() && !is_null($job->salary))
                <span class="tw-job-badge tw-badge-salary">{{ $job->salary }}</span>
              @elseif(is_null($job->salary))
                <span class="tw-job-badge tw-badge-salary">Thỏa thuận</span>
              @else
                <span class="tw-job-badge tw-badge-loc">Đăng nhập xem lương</span>
              @endif
            </div>
            @if(!empty($job->keywords))
              <div class="tw-job-tags">
                @foreach(array_slice(explode(',', $job->keywords), 0, 3) as $kw)
                  <a href="{{ route('jobs', ['keyword' => trim($kw)]) }}" class="tw-job-tag">{{ trim($kw) }}</a>
                @endforeach
              </div>
            @endif
            <div class="tw-job-footer">
              <a href="{{ $detailRoute }}" class="tw-btn-detail">Xem chi tiết</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-4">
      <a href="{{ route('jobs') }}" class="tw-view-all">Xem tất cả việc làm</a>
    </div>
  </div>
</section>
@endif

{{-- ===== CTA BANNER ===== --}}
<section class="tw-section">
  <div class="container">
    <div class="tw-cta">
      <h3>Bạn là nhà tuyển dụng?</h3>
      <p>Tiếp cận hàng nghìn ứng viên chất lượng cao. Đăng tin tuyển dụng miễn phí.</p>
      <div class="tw-cta-btns">
        <a href="{{ route('account.createJob') }}" class="tw-cta-btn-green">Đăng tin tuyển dụng</a>
        <a href="{{ route('cv.builder') }}" class="tw-cta-btn-outline">Tạo CV ngay</a>
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
          $(element).find('i').removeClass('fa-heart-o').addClass('fa-heart');
          toastr.success('Đã thêm vào yêu thích');
        } else {
          $(element).find('i').removeClass('fa-heart').addClass('fa-heart-o');
          toastr.warning('Đã hủy yêu thích');
        }
      }
    }
  });
}
</script>
@endsection
