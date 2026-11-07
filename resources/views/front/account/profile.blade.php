@extends('front.layouts.app')

@section('main')
  <section class="tw-section tw-section-alt" style="padding-top: var(--sp-6);">
    <div class="container">
      
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: var(--text-sm);">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
          <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Thông tin tài khoản</li>
        </ol>
      </nav>

      <div class="row g-4">
        <div class="col-lg-3">
          @include('front.account.sidebar')
        </div>

        <div class="col-lg-9">
          <!-- 1. THÔNG TIN CÁ NHÂN -->
          <div class="tw-content-card">
            <form action="" method="post" id="userForm" name="userForm">
              <h3 class="mb-1">
                @if (Auth::check() && Auth::user()->role === 'employer')
                  Thông tin người tuyển dụng
                @else
                  Thông tin cá nhân
                @endif
              </h3>
              <p class="text-muted small mb-4" style="font-size: var(--text-xs);">
                Các trường đánh dấu <span class="text-danger">*</span> là bắt buộc.
              </p>

              <div class="tw-form-group">
                <label for="name">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" placeholder="Nguyễn Văn A"
                  class="tw-input" value="{{ $user->fullname }}">
                <p class="invalid-feedback d-block small mt-1 text-danger"></p>
              </div>

              <div class="tw-form-group">
                <label for="email">
                  Email <span class="text-danger">*</span>
                  @if (Auth::check() && Auth::user()->role === 'employer')
                    <span class="text-muted fw-normal" style="font-size: var(--text-xs);">(vui lòng dùng email công ty)</span>
                  @endif
                </label>
                <input type="text" name="email" id="email" placeholder="email@domain.com"
                  class="tw-input" value="{{ $user->email }}">
                <p class="invalid-feedback d-block small mt-1 text-danger"></p>
              </div>

              <div class="tw-form-group">
                <label for="mobile">Số điện thoại</label>
                <input type="text" name="mobile" id="mobile" placeholder="0912345678"
                  class="tw-input" value="{{ $user->mobile }}">
                <p class="invalid-feedback d-block small mt-1 text-danger"></p>
              </div>

              <div class="pt-2">
                <button type="submit" class="tw-btn tw-btn-primary">
                  Lưu thay đổi
                </button>
              </div>
            </form>
          </div>

          <!-- 2. THÔNG TIN CÔNG TY (NẾU LÀ EMPLOYER) -->
          @if (Auth::check() && Auth::user()->role === 'employer')
            <div class="tw-content-card">
              <form action="" method="post" id="userFormCompany" name="userFormCompany">
                <h3 class="mb-1">Thông tin công ty (Trụ sở chính)</h3>
                <p class="text-muted small mb-4" style="font-size: var(--text-xs);">
                  Thông tin công ty sẽ xuất hiện công khai trên các tin tuyển dụng của bạn.
                </p>

                <div class="tw-form-group">
                  <label for="company_name">Tên doanh nghiệp <span class="text-danger">*</span></label>
                  <input type="text" name="company_name" id="company_name" placeholder="Tên công ty"
                    class="tw-input" value="{{ $user->company_name }}">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="tw-form-group">
                  <label>Khu vực trụ sở (Theo 34 Tỉnh thành mới)</label>
                  <div class="row g-2 mb-2">
                    <div class="col-md-6">
                      <label class="small text-muted mb-1">Tỉnh / Thành phố</label>
                      <select class="tw-input" id="province" name="province">
                        <option value="">Chọn tỉnh / thành</option>
                        @if (!empty($provinces))
                          @foreach ($provinces as $p)
                            <option value="{{ $p['full_name'] }}" {{ (old('province_name', $user->province) == $p['full_name'] || old('province_name', $user->province) == $p['name']) ? 'selected' : '' }}>
                              {{ $p['full_name'] }}
                            </option>
                          @endforeach
                        @elseif (!empty($user->province))
                          <option selected>{{ $user->province }}</option>
                        @endif
                      </select>
                      <input type="hidden" id="province_name" name="province_name" value="{{ old('province_name', $user->province) }}">
                      <input type="hidden" id="district_name" name="district_name" value="">
                      <input type="hidden" id="ward_name" name="ward_name" value="{{ old('ward_name', $user->wards) }}">
                    </div>

                    <div class="col-md-6">
                      <label class="small text-muted mb-1">Phường / Xã</label>
                      <select class="tw-input" id="wards" name="wards">
                        <option value="">Chọn Phường / Xã</option>
                        @if (!empty($user->wards))
                          <option value="{{ $user->wards }}" selected>{{ $user->wards }}</option>
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="row g-2 mb-2">
                    <div class="col-md-12">
                      <label class="small text-muted mb-1">Địa chỉ chi tiết (Số nhà, tòa nhà, tên đường...)</label>
                      <input type="text" class="tw-input" id="location_detail" name="location_detail"
                        placeholder="Số nhà, toà nhà, đường..." value="{{ $user->location_detail }}">
                    </div>
                  </div>
                </div>

                <div class="tw-form-group">
                  <label for="company_website">Website công ty</label>
                  <input type="text" placeholder="https://company.vn" id="company_website"
                    name="company_website" class="tw-input" value="{{ $user->company_website }}">
                </div>

                <div class="pt-2">
                  <button type="submit" class="tw-btn tw-btn-primary">
                    Cập nhật công ty
                  </button>
                </div>
              </form>
            </div>
          @endif

          <!-- 3. THAY ĐỔI MẬT KHẨU -->
          @if (Auth::check() && Auth::user()->password !== null && Auth::user()->password !== '')
            <div class="tw-content-card">
              <form action="" method="POST" id="changePasswordForm" name="changePasswordForm">
                <h3 class="mb-1">Đổi mật khẩu</h3>
                <p class="text-muted small mb-4" style="font-size: var(--text-xs);">
                  Bạn nên sử dụng mật khẩu mạnh kết hợp chữ cái, số và ký tự đặc biệt.
                </p>

                <div class="tw-form-group">
                  <label for="old_password">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                  <input type="password" name="old_password" id="old_password" placeholder="Nhập mật khẩu hiện tại"
                    class="tw-input">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="tw-form-group">
                  <label for="new_password">Mật khẩu mới <span class="text-danger">*</span></label>
                  <input type="password" name="new_password" id="new_password" placeholder="Tối thiểu 6 ký tự"
                    class="tw-input">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="tw-form-group">
                  <label for="confirm_password">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                  <input type="password" name="confirm_password" id="confirm_password"
                    placeholder="Nhập lại mật khẩu mới" class="tw-input">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="pt-2">
                  <button type="submit" class="tw-btn tw-btn-primary">
                    Cập nhật mật khẩu
                  </button>
                </div>
              </form>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection

@section('customJs')
  <script type="text/javascript">
    $("#userForm").submit(function(e) {
      e.preventDefault();

      $.ajax({
        url: '{{ route('account.updateProfile') }}',
        type: 'put',
        dataType: 'json',
        data: $("#userForm").serializeArray(),
        success: function(response) {
          if (response.status == true) {
            $("#name").removeClass('is-invalid').siblings('p').html('');
            $("#email").removeClass('is-invalid').siblings('p').html('');
            $("#mobile").removeClass('is-invalid').siblings('p').html('');

            window.location.href = "{{ route('account.profile') }}";
          } else {
            var errors = response.errors;
            if (errors.name) {
              $("#name").addClass('is-invalid').siblings('p').html(errors.name);
            } else {
              $("#name").removeClass('is-invalid').siblings('p').html('');
            }
            if (errors.email) {
              $("#email").addClass('is-invalid').siblings('p').html(errors.email);
            } else {
              $("#email").removeClass('is-invalid').siblings('p').html('');
            }
            if (errors.mobile) {
              $("#mobile").addClass('is-invalid').siblings('p').html(errors.mobile);
            } else {
              $("#mobile").removeClass('is-invalid').siblings('p').html('');
            }
          }
        }
      });
    });

    $("#userFormCompany").submit(function(e) {
      e.preventDefault();

      $.ajax({
        url: '{{ route('account.updateProfileCompany') }}',
        type: 'put',
        dataType: 'json',
        data: $("#userFormCompany").serializeArray(),
        success: function(response) {
          if (response.status == true) {
            $("#company_name").removeClass('is-invalid').siblings('p').html('');
            window.location.href = "{{ route('account.profile') }}";
          } else {
            var errors = response.errors;
            if (errors.company_name) {
              $("#company_name").addClass('is-invalid').siblings('p').html(errors.company_name);
            } else {
              $("#company_name").removeClass('is-invalid').siblings('p').html('');
            }
          }
        }
      });
    });

    $("#changePasswordForm").submit(function(e) {
      e.preventDefault();

      $.ajax({
        url: '{{ route('account.updatePassword') }}',
        type: 'post',
        dataType: 'json',
        data: $("#changePasswordForm").serializeArray(),
        success: function(response) {
          if (response.status == true) {
            window.location.href = "{{ route('account.profile') }}";
          } else {
            var errors = response.errors;
            if (errors.old_password) {
              $("#old_password").addClass('is-invalid').siblings('p').html(errors.old_password);
            } else {
              $("#old_password").removeClass('is-invalid').siblings('p').html('');
            }
            if (errors.new_password) {
              $("#new_password").addClass('is-invalid').siblings('p').html(errors.new_password);
            } else {
              $("#new_password").removeClass('is-invalid').siblings('p').html('');
            }
            if (errors.confirm_password) {
              $("#confirm_password").addClass('is-invalid').siblings('p').html(errors.confirm_password);
            } else {
              $("#confirm_password").removeClass('is-invalid').siblings('p').html('');
            }
          }
        }
      });
    });
  </script>

  <script>
    $(document).ready(function() {
      var currentWard = "{{ old('ward_name', $user->wards) }}";

      function loadWards(provinceVal, selectedWard) {
        if (!provinceVal) {
          $("#wards").html('<option value="">Chọn Phường / Xã</option>');
          $("#ward_name").val('');
          return;
        }

        $("#wards").html('<option value="">Đang tải xã / phường...</option>');

        $.getJSON('/api/proxy/wards/' + encodeURIComponent(provinceVal), function(response) {
          var html = '<option value="">Chọn Phường / Xã</option>';
          if (response && response.data && response.data.length > 0) {
            $.each(response.data, function(index, item) {
              var isSelected = (selectedWard && (selectedWard == item.full_name || selectedWard == item.name)) ? 'selected' : '';
              html += '<option value="' + item.full_name + '" ' + isSelected + '>' + item.full_name + '</option>';
            });
          }
          $("#wards").html(html);

          if ($("#wards").val()) {
            $("#ward_name").val($("#wards").val());
          }
        }).fail(function() {
          $("#wards").html('<option value="">Chọn Phường / Xã</option>');
        });
      }

      $("#province").change(function() {
        var selectedProvince = $(this).val();
        $("#province_name").val(selectedProvince);
        $("#ward_name").val('');
        loadWards(selectedProvince, '');
      });

      $("#wards").change(function() {
        var selectedWard = $(this).val();
        $("#ward_name").val(selectedWard);
      });

      var initialProvince = $("#province").val();
      if (initialProvince) {
        loadWards(initialProvince, currentWard);
      }
    });
  </script>
@endsection
