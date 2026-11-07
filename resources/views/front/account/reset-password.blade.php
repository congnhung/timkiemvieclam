@extends('front.layouts.app')

@section('main')
  <div class="tw-auth-wrap">
    <div class="tw-auth-card">
      <h1 class="tw-auth-title">Đổi mật khẩu</h1>
      <p class="tw-auth-sub">Tạo mật khẩu mới cho tài khoản của bạn</p>

      <form action="{{ route('account.processResetPassword') }}" method="post">
        @csrf
        <input type="hidden" name="token" value="{{ $tokenString }}">

        <div class="tw-form-group">
          <label for="new_password">Mật khẩu mới</label>
          <input type="password" name="new_password" id="new_password"
            class="tw-input @error('new_password') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự">
          @error('new_password')
            <p class="invalid-feedback d-block small mt-1 text-danger">{{ $message }}</p>
          @enderror
        </div>

        <div class="tw-form-group">
          <label for="confirm_password">Xác nhận mật khẩu mới</label>
          <input type="password" name="confirm_password" id="confirm_password"
            class="tw-input @error('confirm_password') is-invalid @enderror" placeholder="Nhập lại mật khẩu mới">
          @error('confirm_password')
            <p class="invalid-feedback d-block small mt-1 text-danger">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit" class="tw-btn tw-btn-primary w-100 mt-3 text-center justify-content-center">
          Xác nhận đổi mật khẩu
        </button>
      </form>

      <div class="mt-4 pt-3 border-top text-center" style="font-size: var(--text-sm);">
        <p class="text-muted mb-0">
          Quay lại 
          <a href="{{ route('account.login') }}" class="text-decoration-none fw-medium" style="color: var(--c-brand);">
            Đăng nhập
          </a>
        </p>
      </div>
    </div>
  </div>
@endsection
