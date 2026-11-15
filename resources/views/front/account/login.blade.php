@extends('front.layouts.app')

@section('main')
  <div class="tw-auth-wrap">
    <div class="tw-auth-card">
      <h1 class="tw-auth-title">Đăng nhập</h1>
      <p class="tw-auth-sub">Chào mừng bạn quay lại với TopWork</p>

      @if (session()->has('warning'))
        <div class="alert alert-warning py-2.5 px-3 mb-3 border border-warning-subtle text-start" style="font-size: 13.5px; border-radius: 8px;">
          <strong>⏳ Đang chờ xét duyệt:</strong><br>
          {{ session('warning') }}
        </div>
      @endif

      @if (session()->has('error'))
        <div class="alert alert-danger py-2.5 px-3 mb-3 border border-danger-subtle text-start" style="font-size: 13.5px; border-radius: 8px;">
          {{ session('error') }}
        </div>
      @endif

      <form action="{{ route('account.authenticate') }}" method="post">
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

        <div class="tw-form-group">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="password" class="mb-0">Mật khẩu</label>
            <a href="{{ route('account.forgotPassword') }}" class="small text-decoration-none" style="color: var(--c-brand); font-size: var(--text-xs);">
              Quên mật khẩu?
            </a>
          </div>
          <input type="password" name="password" id="password"
            class="tw-input @error('password') is-invalid @enderror"
            placeholder="••••••••">
          @error('password')
            <p class="invalid-feedback d-block small mt-1 text-danger">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit" class="tw-btn tw-btn-primary w-100 mt-3 text-center justify-content-center">
          Đăng nhập
        </button>
      </form>

      <div class="mt-4 pt-3 border-top text-center" style="font-size: var(--text-sm);">
        <p class="text-muted mb-0">
          Chưa có tài khoản? 
          <a href="{{ route('account.registration') }}" class="text-decoration-none fw-medium" style="color: var(--c-brand);">
            Đăng ký ngay
          </a>
        </p>
      </div>
    </div>
  </div>
@endsection
