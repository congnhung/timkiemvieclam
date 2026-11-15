@extends('front.layouts.app')

@section('main')
  <div class="tw-auth-wrap">
    <div class="tw-auth-card">
      <h1 class="tw-auth-title">Đăng ký tài khoản</h1>
      <p class="tw-auth-sub">Tạo tài khoản để tìm việc hoặc tuyển dụng nhân tài</p>

      <div id="formSection">
        <form action="" name="registrationForm" id="registrationForm">
          <div class="tw-form-group">
            <label for="role">Mục đích sử dụng</label>
            <select name="role" id="role" class="tw-input">
              <option value="user">Người tìm việc làm</option>
              <option value="employer">Nhà tuyển dụng / Doanh nghiệp</option>
            </select>
            <p class="invalid-feedback d-block small mt-1 text-danger"></p>
          </div>

          <div id="employerFields" style="display: none;">
            <div class="tw-form-group">
              <label for="company_name">Tên công ty / Doanh nghiệp <span class="text-danger">*</span></label>
              <input type="text" name="company_name" id="company_name" class="tw-input" placeholder="Ví dụ: Công ty Cổ phần Công nghệ ABC">
              <p class="invalid-feedback d-block small mt-1 text-danger"></p>
            </div>

            <div class="tw-form-group">
              <label for="mobile">Số điện thoại liên hệ <span class="text-danger">*</span></label>
              <input type="text" name="mobile" id="mobile" class="tw-input" placeholder="Ví dụ: 0912345678">
              <p class="invalid-feedback d-block small mt-1 text-danger"></p>
            </div>

            <div class="tw-form-group">
              <label for="location_detail">Địa chỉ trụ sở công ty <span class="text-danger">*</span></label>
              <input type="text" name="location_detail" id="location_detail" class="tw-input" placeholder="Ví dụ: Số 10 Phạm Văn Bạch, Cầu Giấy, Hà Nội">
              <p class="invalid-feedback d-block small mt-1 text-danger"></p>
            </div>

            <div class="tw-form-group">
              <label for="company_website">Website công ty (nếu có)</label>
              <input type="text" name="company_website" id="company_website" class="tw-input" placeholder="Ví dụ: https://congtyabc.com">
              <p class="invalid-feedback d-block small mt-1 text-danger"></p>
            </div>
          </div>

          <div class="tw-form-group">
            <label for="name" id="nameLabel">Họ và tên <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="tw-input" placeholder="Nguyễn Văn A">
            <p class="invalid-feedback d-block small mt-1 text-danger"></p>
          </div> 

          <div class="tw-form-group">
            <label for="email">Email đăng nhập <span class="text-danger">*</span></label>
            <input type="text" name="email" id="email" class="tw-input" placeholder="name@example.com">
            <p class="invalid-feedback d-block small mt-1 text-danger"></p>
          </div> 

          <div class="tw-form-group">
            <label for="password">Mật khẩu <span class="text-danger">*</span></label>
            <input type="password" name="password" id="password" class="tw-input" placeholder="Tối thiểu 6 ký tự">
            <p class="invalid-feedback d-block small mt-1 text-danger"></p>
          </div> 

          <div class="tw-form-group">
            <label for="confirm_password">Xác nhận mật khẩu <span class="text-danger">*</span></label>
            <input type="password" name="confirm_password" id="confirm_password" class="tw-input" placeholder="Nhập lại mật khẩu">
            <p class="invalid-feedback d-block small mt-1 text-danger"></p>
          </div> 

          <button type="submit" class="tw-btn tw-btn-primary w-100 mt-3 text-center justify-content-center" id="submitBtn">
            Tạo tài khoản
          </button>
        </form>

        <div class="mt-4 pt-3 border-top text-center" style="font-size: var(--text-sm);">
          <p class="text-muted mb-0">
            Đã có tài khoản? 
            <a href="{{ route('account.login') }}" class="text-decoration-none fw-medium" style="color: var(--c-brand);">
              Đăng nhập
            </a>
          </p>
        </div>
      </div>

      {{-- Card xác nhận Đang chờ xét duyệt --}}
      <div id="pendingReviewCard" style="display: none; text-align: center; padding: 15px 0;">
        <div style="width: 72px; height: 72px; margin: 0 auto 16px; border-radius: 50%; background: #fffbe6; border: 2px solid #ffe58f; display: flex; align-items: center; justify-content: center; font-size: 36px;">
          ⏳
        </div>
        <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Đăng ký tài khoản thành công!</h2>
        <div style="display: inline-block; background: #fff8e6; border: 1px solid #ffd591; color: #d46b08; padding: 4px 14px; border-radius: 20px; font-size: 12.5px; font-weight: 600; margin-bottom: 14px;">
          Trạng thái: Đang chờ Quản trị viên xem xét
        </div>
        <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin-bottom: 16px;">
          Thông tin của <strong id="reviewCompanyName">Doanh nghiệp</strong> đã được gửi đến Ban Quản Trị TopWork để xác minh và kích hoạt tài khoản.
        </p>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; text-align: left; font-size: 13px; margin-bottom: 18px; line-height: 1.8;">
          <div><strong>🏢 Tên công ty:</strong> <span id="infoCompanyName">...</span></div>
          <div><strong>👤 Người đại diện:</strong> <span id="infoFullname">...</span></div>
          <div><strong>✉️ Email đăng nhập:</strong> <span id="infoEmail">...</span></div>
          <div><strong>📞 Số điện thoại:</strong> <span id="infoMobile">...</span></div>
          <div><strong>📍 Địa chỉ:</strong> <span id="infoAddress">...</span></div>
        </div>

        <div class="d-flex gap-2 justify-content-center">
          <a href="{{ route('home') }}" class="tw-btn tw-btn-outline">Về trang chủ</a>
          <a href="{{ route('account.login') }}" class="tw-btn tw-btn-primary">Đến trang Đăng nhập</a>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('customJs')
  <script>
    $('#role').change(function() {
      if ($(this).val() === 'employer') {
        $('#employerFields').slideDown(200);
        $('#nameLabel').html('Họ và tên người đại diện <span class="text-danger">*</span>');
        $('#submitBtn').text('Đăng ký tài khoản Nhà Tuyển Dụng');
      } else {
        $('#employerFields').slideUp(200);
        $('#nameLabel').html('Họ và tên <span class="text-danger">*</span>');
        $('#submitBtn').text('Tạo tài khoản');
      }
    });

    $("#registrationForm").submit(function (e) {
      e.preventDefault();

      $.ajax({
        url: '{{ route("account.processRegistration") }}',
        type: 'post',
        data: $('#registrationForm').serializeArray(),
        dataType: 'json',
        success: function (response) {
          ['name', 'email', 'password', 'confirm_password', 'company_name', 'mobile', 'location_detail', 'company_website'].forEach(function (field) {
            $("#" + field).removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
          });

          if (response.status === false) {
            $.each(response.errors, function (field, message) {
              $("#" + field).addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(message);
            });
          } else {
            if (response.is_employer) {
              $('#formSection').hide();
              const d = response.data || {};
              $('#reviewCompanyName').text(d.company_name || 'Doanh nghiệp');
              $('#infoCompanyName').text(d.company_name || '---');
              $('#infoFullname').text(d.fullname || '---');
              $('#infoEmail').text(d.email || '---');
              $('#infoMobile').text(d.mobile || '---');
              $('#infoAddress').text(d.location_detail || '---');
              $('#pendingReviewCard').fadeIn(300);
            } else {
              window.location.href = '{{ route("account.login") }}';
            }
          }
        }
      });
    });
  </script>
@endsection
