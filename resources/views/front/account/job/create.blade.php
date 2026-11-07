@extends('front.layouts.app')

@section('main')
  <section class="tw-section tw-section-alt" style="padding-top: var(--sp-6);">
    <div class="container">
      
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: var(--text-sm);">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('account.myJobs') }}" class="text-decoration-none text-muted">Tin đã đăng</a></li>
          <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Đăng tin tuyển dụng</li>
        </ol>
      </nav>

      <div class="row g-4">
        <div class="col-lg-3">
          @include('front.account.sidebar')
        </div>

        <div class="col-lg-9">
          <form action="" method="post" id="createJobForm" name="createJobForm">
            <div class="tw-content-card">
              <h3 class="mb-1 border-0 pb-0">Nội dung tin tuyển dụng</h3>
              <p class="text-muted small mb-4" style="font-size: var(--text-xs);">
                Các trường có dấu <span class="text-danger">*</span> là bắt buộc.
              </p>

              <div class="row g-3">
                <div class="col-md-6 tw-form-group">
                  <label for="title">Tiêu đề công việc <span class="text-danger">*</span></label>
                  <input type="text" placeholder="Ví dụ: Senior Laravel Developer" id="title" name="title"
                    class="tw-input" value="{{ old('title') }}">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="choices-single-default">Ngành nghề <span class="text-danger">*</span></label>
                  <select id="choices-single-default" name="category" class="tw-input">
                    @if ($careers->isNotEmpty())
                      @foreach ($careers as $career)
                        <option value="{{ $career->id }}" {{ old('category') == $career->id ? 'selected' : '' }}>
                          {{ $career->name }}
                        </option>
                      @endforeach
                    @endif
                  </select>
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="jobType">Hình thức làm việc <span class="text-danger">*</span></label>
                  <select name="jobType" id="jobType" class="tw-input">
                    @if ($jobtypes->isNotEmpty())
                      @foreach ($jobtypes as $jobtype)
                        <option value="{{ $jobtype->id }}" {{ old('jobType') == $jobtype->id ? 'selected' : '' }}>
                          {{ $jobtype->name }}
                        </option>
                      @endforeach
                    @endif
                  </select>
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="vacancy">Số lượng tuyển <span class="text-danger">*</span></label>
                  <input type="number" min="1" placeholder="Ví dụ: 3" id="vacancy" name="vacancy"
                    class="tw-input" value="{{ old('vacancy') }}">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="salary">Mức lương</label>
                  <input type="text" placeholder="Ví dụ: 15 - 20 triệu hoặc Thỏa thuận" id="salary" name="salary"
                    class="tw-input" value="{{ old('salary') }}">
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="level">Vị trí / Cấp bậc <span class="text-danger">*</span></label>
                  <input type="text" placeholder="Ví dụ: Nhân viên, Trưởng nhóm" id="level" name="level"
                    class="tw-input" value="{{ old('level') }}">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>
              </div>

              <div class="tw-form-group mt-3">
                <label for="description">Mô tả công việc</label>
                <textarea class="textarea" name="description" id="description" cols="5" rows="5"
                  placeholder="Mô tả công việc cụ thể...">{{ old('description') }}</textarea>
              </div>

              <div class="tw-form-group mt-3">
                <label for="benefits">Quyền lợi & Phúc lợi</label>
                <textarea class="textarea" name="benefits" id="benefits" cols="5" rows="5"
                  placeholder="Lương tháng 13, bảo hiểm, du lịch...">{{ old('benefits') }}</textarea>
              </div>

              <div class="tw-form-group mt-3">
                <label for="responsibility">Trách nhiệm công việc</label>
                <textarea class="textarea" name="responsibility" id="responsibility" cols="5" rows="5"
                  placeholder="Các đầu việc chính...">{{ old('responsibility') }}</textarea>
              </div>

              <div class="tw-form-group mt-3">
                <label for="qualifications">Yêu cầu ứng viên</label>
                <textarea class="textarea" name="qualifications" id="qualifications" cols="5" rows="5"
                  placeholder="Kinh nghiệm, bằng cấp, kỹ năng...">{{ old('qualifications') }}</textarea>
              </div>

              <div class="row g-3 mt-1">
                <div class="col-md-6 tw-form-group">
                  <label for="experience">Kinh nghiệm tối thiểu</label>
                  <select name="experience" id="experience" class="tw-input">
                    <option value="1" {{ old('experience') == '1' ? 'selected' : '' }}>1 năm</option>
                    <option value="2" {{ old('experience') == '2' ? 'selected' : '' }}>2 năm</option>
                    <option value="3" {{ old('experience') == '3' ? 'selected' : '' }}>3 năm</option>
                    <option value="4" {{ old('experience') == '4' ? 'selected' : '' }}>4 năm</option>
                    <option value="5" {{ old('experience') == '5' ? 'selected' : '' }}>5 năm</option>
                    <option value="6" {{ old('experience') == '6' ? 'selected' : '' }}>6 năm</option>
                    <option value="7" {{ old('experience') == '7' ? 'selected' : '' }}>7 năm</option>
                    <option value="8" {{ old('experience') == '8' ? 'selected' : '' }}>8 năm</option>
                    <option value="9" {{ old('experience') == '9' ? 'selected' : '' }}>9 năm</option>
                    <option value="10" {{ old('experience') == '10' ? 'selected' : '' }}>10 năm</option>
                    <option value="10_plus" {{ old('experience') == '10_plus' ? 'selected' : '' }}>Trên 10 năm</option>
                  </select>
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="expiration_date">Hạn chót nhận hồ sơ</label>
                  <input type="date" name="expiration_date" id="expiration_date" class="tw-input"
                    value="{{ old('expiration_date') }}">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>
              </div>

              <div class="tw-form-group mt-3">
                <label for="keywords">Từ khóa kỹ năng (Tags)</label>
                <input type="text" id="keywords" name="keywords" class="tw-input" placeholder="Ví dụ: PHP, Laravel, MySQL">
              </div>

              <!-- THÔNG TIN DOANH NGHIỆP -->
              <h3 class="mb-1 border-0 pb-0 mt-5 pt-4 border-top">Thông tin công ty</h3>
              <p class="text-muted small mb-4" style="font-size: var(--text-xs);">
                Địa chỉ và website công ty hiển thị trên tin tuyển dụng.
              </p>

              <div class="tw-form-group">
                <label for="company_name">Tên doanh nghiệp <span class="text-danger">*</span></label>
                <input type="text" placeholder="Tên công ty" id="company_name" name="company_name"
                  class="tw-input" value="{{ old('company_name', $user->company_name) }}">
                <p class="invalid-feedback d-block small mt-1 text-danger"></p>
              </div>

              <div class="tw-form-group">
                <label>Địa điểm làm việc (Theo 34 Tỉnh thành mới)</label>
                <div class="row g-2 mb-2">
                  <div class="col-md-6">
                    <label class="small text-muted mb-1">Tỉnh / Thành phố</label>
                    <select class="tw-input" id="province" name="province">
                      <option value="">Chọn tỉnh / thành</option>
                      @if(!empty($provinces))
                        @foreach ($provinces as $p)
                          <option value="{{ $p['full_name'] }}" {{ (old('province_name', $user->province) == $p['full_name'] || old('province_name', $user->province) == $p['name']) ? 'selected' : '' }}>
                            {{ $p['full_name'] }}
                          </option>
                        @endforeach
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
                    <label class="small text-muted mb-1">Địa chỉ cụ thể (Tòa nhà, số nhà, tên đường...)</label>
                    <input type="text" class="tw-input" id="location_detail" name="location_detail"
                      placeholder="Ví dụ: Tòa nhà Bitexco, Số 2 Hải Triều..."
                      value="{{ old('location_detail', $user->location_detail) }}">
                  </div>
                </div>
              </div>

              <div class="tw-form-group">
                <label for="company_website">Website công ty</label>
                <input type="text" placeholder="https://topwork.vn" id="company_website"
                  name="company_website" class="tw-input"
                  value="{{ old('company_website', $user->company_website) }}">
              </div>

              <div class="pt-4 border-top mt-4">
                <button type="submit" class="tw-btn tw-btn-primary">
                  Đăng tin tuyển dụng
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('customJs')
  <script type="text/javascript">
    $("#createJobForm").submit(function(e) {
      e.preventDefault();
      $("button[type=submit]").prop("disabled", true);
      $.ajax({
        url: '{{ route('account.saveJob') }}',
        type: 'POST',
        dataType: 'json',
        data: $("#createJobForm").serializeArray(),
        success: function(response) {
          $("button[type=submit]").prop("disabled", false);
          if (response.status == true) {
            $("#title").removeClass('is-invalid').siblings('p').html('');
            $("#category").removeClass('is-invalid').siblings('p').html('');
            $("#jobType").removeClass('is-invalid').siblings('p').html('');
            $("#vacancy").removeClass('is-invalid').siblings('p').html('');
            $("#level").removeClass('is-invalid').siblings('p').html('');
            $("#company_name").removeClass('is-invalid').siblings('p').html('');
            $("#expiration_date").removeClass('is-invalid').siblings('p').html('');

            window.location.href = "{{ route('account.myJobs') }}";
          } else {
            var errors = response.errors;
            toastr.warning('Có lỗi xảy ra, hãy kiểm tra lại thông tin!');
            
            if (errors.title) {
              $("#title").addClass('is-invalid').siblings('p').html(errors.title);
            } else {
              $("#title").removeClass('is-invalid').siblings('p').html('');
            }
            if (errors.category) {
              $("#category").addClass('is-invalid').siblings('p').html(errors.category);
            } else {
              $("#category").removeClass('is-invalid').siblings('p').html('');
            }
            if (errors.jobType) {
              $("#jobType").addClass('is-invalid').siblings('p').html(errors.jobType);
            } else {
              $("#jobType").removeClass('is-invalid').siblings('p').html('');
            }
            if (errors.vacancy) {
              $("#vacancy").addClass('is-invalid').siblings('p').html(errors.vacancy);
            } else {
              $("#vacancy").removeClass('is-invalid').siblings('p').html('');
            }
            if (errors.level) {
              $("#level").addClass('is-invalid').siblings('p').html(errors.level);
            } else {
              $("#level").removeClass('is-invalid').siblings('p').html('');
            }
            if (errors.company_name) {
              $("#company_name").addClass('is-invalid').siblings('p').html(errors.company_name);
            } else {
              $("#company_name").removeClass('is-invalid').siblings('p').html('');
            }
            if (errors.expiration_date) {
              $("#expiration_date").addClass('is-invalid').siblings('p').html(errors.expiration_date);
            } else {
              $("#expiration_date").removeClass('is-invalid').siblings('p').html('');
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

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const element = document.getElementById('choices-single-default');
      if (element) {
        new Choices(element, {
          searchEnabled: true,
          placeholder: true,
          placeholderValue: 'Chọn ngành nghề',
          itemSelectText: 'Nhấn để chọn',
        });
      }

      const keywordInput = document.getElementById('keywords');
      if (keywordInput) {
        new Choices(keywordInput, {
          removeItemButton: true,
          uniqueItemText: 'Từ khóa này đã có',
          placeholder: true,
          placeholderValue: 'Nhập từ khóa (Ví dụ: PHP, Java)',
          delimiter: ',',
          editItems: true,
          removeItems: true
        });
      }
    });
  </script>
@endsection
