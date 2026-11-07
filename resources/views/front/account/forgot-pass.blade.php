@extends('front.layouts.app')

@section('main')
  <div class="tw-auth-wrap">
    <div class="tw-auth-card">
      <h1 class="tw-auth-title">Quên mật khẩu</h1>
      <p class="tw-auth-sub">Nhập email tài khoản để nhận hướng dẫn đặt lại mật khẩu</p>

      <form action="{{ route('account.processForgotPassword') }}" method="post">
        @csrf
        <div class="tw-form-group">
          <label for="email">Email</label>
          <input type="text" name="email" id="email" value="{{ old('email') }}"
            class="tw-input @error('email') is-invalid @enderror" 
            placeholder="name@example.com">
          @error('email')
            <p class="invalid-feedback d-block small mt-1 text-danger">{{ $message }}</p>
          @enderror
        </div> 

        <button type="submit" class="tw-btn tw-btn-primary w-100 mt-3 text-center justify-content-center">
          Gửi yêu cầu
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
