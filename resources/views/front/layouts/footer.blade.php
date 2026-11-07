<footer class="tw-footer">
  <div class="container">
    <div class="tw-footer-top">
      <div class="tw-footer-brand">
        <img src="{{ asset('assets/user/images/logo_web_footer.png') }}" alt="TopWork" height="34">
        <p class="tw-footer-tagline">Nền tảng tuyển dụng & tìm việc làm hàng đầu Việt Nam</p>
        <div class="tw-footer-socials">
          <a href="http://facebook.com/" target="_blank">fb</a>
          <a href="https://www.linkedin.com/" target="_blank">in</a>
          <a href="https://twitter.com/" target="_blank">tw</a>
        </div>
      </div>

      <div class="tw-footer-col">
        <h6 class="tw-footer-col-title">Ứng viên</h6>
        <ul>
          <li><a href="{{ route('jobs') }}">Tìm việc làm</a></li>
          <li><a href="{{ route('cv.builder') }}">Tạo CV Online</a></li>
          @if(Auth::check() && Auth::user()->role === 'user')
            <li><a href="{{ route('cv.myResumes') }}">CV của tôi</a></li>
            <li><a href="{{ route('account.savedJobs') }}">Việc đã lưu</a></li>
          @else
            <li><a href="{{ route('account.login') }}">Đăng nhập</a></li>
            <li><a href="{{ route('account.registration') }}">Đăng ký miễn phí</a></li>
          @endif
        </ul>
      </div>

      <div class="tw-footer-col">
        <h6 class="tw-footer-col-title">Nhà tuyển dụng</h6>
        <ul>
          @if(Auth::check() && Auth::user()->role === 'employer')
            <li><a href="{{ route('account.createJob') }}">Đăng tin tuyển dụng</a></li>
            <li><a href="{{ route('account.myJobs') }}">Quản lý tin đăng</a></li>
            <li><a href="{{ route('account.profile') }}">Hồ sơ công ty</a></li>
          @else
            <li><a href="{{ route('account.registration') }}">Đăng ký tuyển dụng</a></li>
            <li><a href="{{ route('account.login') }}">Đăng nhập</a></li>
          @endif
        </ul>
      </div>

      <div class="tw-footer-col">
        <h6 class="tw-footer-col-title">Liên hệ</h6>
        <ul>
          <li>0123.456.789</li>
          <li>contact@topwork.vn</li>
          <li>Hà Nội, Việt Nam</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="tw-footer-bottom">
    <div class="container">
      <div class="tw-footer-bottom-inner">
        <span>© {{ date('Y') }} <strong>TopWork.vn</strong> — Tuyển dụng trực tuyến uy tín</span>
        <span>Designed by <a href="#" class="tw-footer-credit">CôngNhung</a></span>
      </div>
    </div>
  </div>
</footer>
