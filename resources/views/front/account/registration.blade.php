@extends('front.layouts.app')

@section('main')
  <div class="tw-auth-wrap">
    <div class="tw-auth-card">
      <h1 class="tw-auth-title">Đăng ký tài khoản</h1>
      <p class="tw-auth-sub">Tạo tài khoản để tìm việc hoặc tuyển dụng nhân tài</p>

      <form action="" name="registrationForm" id="registrationForm">
        <div class="tw-form-group">
          <label for="name">Họ và tên</label>
          <input type="text" name="name" id="name" class="tw-input" placeholder="Nguyễn Văn A">
          <p class="invalid-feedback d-block small mt-1 text-danger"></p>
        </div> 

        <div class="tw-form-group">
          <label for="email">Email</label>
          <input type="text" name="email" id="email" class="tw-input" placeholder="name@example.com">
          <p class="invalid-feedback d-block small mt-1 text-danger"></p>
        </div> 

        <div class="tw-form-group">
          <label for="password">Mật khẩu</label>
          <input type="password" name="password" id="password" class="tw-input" placeholder="Tối thiểu 6 ký tự">
          <p class="invalid-feedback d-block small mt-1 text-danger"></p>
        </div> 

        <div class="tw-form-group">
          <label for="confirm_password">Xác nhận mật khẩu</label>
          <input type="password" name="confirm_password" id="confirm_password" class="tw-input" placeholder="Nhập lại mật khẩu">
          <p class="invalid-feedback d-block small mt-1 text-danger"></p>
        </div> 

        <div class="tw-form-group">
          <label for="role">Mục đích sử dụng</label>
          <select name="role" id="role" class="tw-input">
            <option value="user">Người tìm việc làm</option>
            <option value="employer">Nhà tuyển dụng</option>
          </select>
          <p class="invalid-feedback d-block small mt-1 text-danger"></p>
        </div>

        <button type="submit" class="tw-btn tw-btn-primary w-100 mt-3 text-center justify-content-center">
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
  </div>
@endsection

@section('customJs')
  <script>
    $("#registrationForm").submit(function (e) {
      e.preventDefault();

      $.ajax({
        url: '{{ route("account.processRegistration") }}',
        type: 'post',
        data: $('#registrationForm').serializeArray(),
        dataType: 'json',
        success: function (response) {
          ['name', 'email', 'password', 'confirm_password'].forEach(function (field) {
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
            window.location.href = '{{ route("account.login") }}';
          }
        }
      });
    });
  </script>
@endsection
