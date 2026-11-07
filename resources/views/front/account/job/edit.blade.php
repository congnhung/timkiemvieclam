@extends('front.layouts.app')

@section('main')
  <section class="tw-section tw-section-alt" style="padding-top: var(--sp-6);">
    <div class="container">
      
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: var(--text-sm);">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
          <li class="breadcrumb-item"><a href="{{ route('account.myJobs') }}" class="text-decoration-none text-muted">Tin đã đăng</a></li>
          <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Chỉnh sửa tin tuyển dụng</li>
        </ol>
      </nav>

      <div class="row g-4">
        <div class="col-lg-3">
          @include('front.account.sidebar')
        </div>

        <div class="col-lg-9">
          <form action="{{ route('account.updateJob', $job->id) }}" method="post" id="editJobForm" name="editJobForm">
            @csrf
            <div class="tw-content-card">
              <h3 class="mb-1 border-0 pb-0">Chỉnh sửa tin tuyển dụng</h3>
              <p class="text-muted small mb-4" style="font-size: var(--text-xs);">
                Các trường có dấu <span class="text-danger">*</span> là bắt buộc.
              </p>

              <div class="row g-3">
                <div class="col-md-6 tw-form-group">
                  <label for="title">Tiêu đề công việc <span class="text-danger">*</span></label>
                  <input type="text" placeholder="Tiêu đề công việc" id="title" name="title"
                    class="tw-input" value="{{ $job->title }}">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="choices-single-default">Ngành nghề <span class="text-danger">*</span></label>
                  <select name="category" id="choices-single-default" class="tw-input">
                    <option value="">Chọn ngành nghề</option>
                    @if ($careers->isNotEmpty())
                      @foreach ($careers as $career)
                        <option {{ $job->career_id == $career->id ? 'selected' : '' }} value="{{ $career->id }}">
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
                    <option value="">Chọn hình thức làm việc</option>
                    @if ($jobtypes->isNotEmpty())
                      @foreach ($jobtypes as $jobtype)
                        <option {{ $job->job_type_id == $jobtype->id ? 'selected' : '' }} value="{{ $jobtype->id }}">
                          {{ $jobtype->name }}
                        </option>
                      @endforeach
                    @endif
                  </select>
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="vacancy">Số lượng tuyển <span class="text-danger">*</span></label>
                  <input type="number" min="1" placeholder="Số lượng" id="vacancy" name="vacancy"
                    class="tw-input" value="{{ $job->vacancy }}">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="salary">Mức lương</label>
                  <input type="text" placeholder="Mức lương" id="salary" name="salary" class="tw-input"
                    value="{{ $job->salary }}">
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="level">Vị trí / Cấp bậc <span class="text-danger">*</span></label>
                  <input type="text" placeholder="Cấp bậc" id="level" name="level" class="tw-input"
                    value="{{ $job->job_level }}">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>
              </div>

              <div class="tw-form-group mt-3">
                <label for="description">Mô tả công việc</label>
                <textarea class="textarea" name="description" id="description" cols="5" rows="5"
                  placeholder="Mô tả công việc cụ thể...">{{ $job->description }}</textarea>
              </div>

              <div class="tw-form-group mt-3">
                <label for="benefits">Quyền lợi & Phúc lợi</label>
                <textarea class="textarea" name="benefits" id="benefits" cols="5" rows="5" placeholder="Phúc lợi">{{ $job->benefits }}</textarea>
              </div>

              <div class="tw-form-group mt-3">
                <label for="responsibility">Trách nhiệm công việc</label>
                <textarea class="textarea" name="responsibility" id="responsibility" cols="5" rows="5"
                  placeholder="Trách nhiệm">{{ $job->responsibility }}</textarea>
              </div>

              <div class="tw-form-group mt-3">
                <label for="qualifications">Yêu cầu ứng viên</label>
                <textarea class="textarea" name="qualifications" id="qualifications" cols="5" rows="5"
                  placeholder="Nhập yêu cầu công việc...">{{ $job->qualifications }}</textarea>
              </div>

              <div class="row g-3 mt-1">
                <div class="col-md-6 tw-form-group">
                  <label for="experience">Kinh nghiệm tối thiểu</label>
                  <select name="experience" id="experience" class="tw-input">
                    <option value="1" {{ $job->experience == 1 ? 'selected' : '' }}>1 năm</option>
                    <option value="2" {{ $job->experience == 2 ? 'selected' : '' }}>2 năm</option>
                    <option value="3" {{ $job->experience == 3 ? 'selected' : '' }}>3 năm</option>
                    <option value="4" {{ $job->experience == 4 ? 'selected' : '' }}>4 năm</option>
                    <option value="5" {{ $job->experience == 5 ? 'selected' : '' }}>5 năm</option>
                    <option value="6" {{ $job->experience == 6 ? 'selected' : '' }}>6 năm</option>
                    <option value="7" {{ $job->experience == 7 ? 'selected' : '' }}>7 năm</option>
                    <option value="8" {{ $job->experience == 8 ? 'selected' : '' }}>8 năm</option>
                    <option value="9" {{ $job->experience == 9 ? 'selected' : '' }}>9 năm</option>
                    <option value="10" {{ $job->experience == 10 ? 'selected' : '' }}>10 năm</option>
                    <option value="10_plus" {{ $job->experience == '10_plus' ? 'selected' : '' }}>Trên 10 năm</option>
                  </select>
                </div>

                <div class="col-md-6 tw-form-group">
                  <label for="expiration_date">Hạn chót nhận hồ sơ</label>
                  <input type="date" name="expiration_date" id="expiration_date" class="tw-input"
                    value="{{ $job->expiration_date }}">
                  <p class="invalid-feedback d-block small mt-1 text-danger"></p>
                </div>
              </div>

              <div class="tw-form-group mt-3">
                <label for="keywords">Từ khóa kỹ năng (Tags)</label>
                <input type="text" id="keywords" name="keywords" class="tw-input"
                  placeholder="Ví dụ: PHP, Java,..." value="{{ str_replace(['[', ']', '"'], '', $job->keywords) }}">
              </div>

              <!-- THÔNG TIN DOANH NGHIỆP -->
              <h3 class="mb-1 border-0 pb-0 mt-5 pt-4 border-top">Chi tiết công ty</h3>
              <p class="text-muted small mb-4" style="font-size: var(--text-xs);">
                Địa chỉ và website công ty hiển thị trên tin tuyển dụng.
              </p>

              <div class="tw-form-group">
                <label for="company_name">Tên doanh nghiệp <span class="text-danger">*</span></label>
                <input type="text" placeholder="Tên công ty" id="company_name" name="company_name"
                  class="tw-input" value="{{ $job->company_name }}">
                <p class="invalid-feedback d-block small mt-1 text-danger"></p>
              </div>

              <div class="tw-form-group">
                <label>Địa điểm làm việc</label>
                <div class="row g-2 mb-2">
                  <div class="col-md-6">
                    <label class="small text-muted mb-1">Tỉnh / Thành phố (34 Tỉnh thành)</label>
                    <select class="tw-input" id="province" name="province">
                      <option value="">Chọn tỉnh / thành</option>
                      @if (!empty($provinces))
                        @foreach ($provinces as $p)
                          <option value="{{ $p['full_name'] }}" {{ (old('province_name', $job->province) == $p['full_name'] || old('province_name', $job->province) == $p['name']) ? 'selected' : '' }}>
                            {{ $p['full_name'] }}
                          </option>
                        @endforeach
                      @elseif (!empty($job->province))
                        <option selected>{{ $job->province }}</option>
                      @endif
                    </select>
                    <input type="hidden" id="province_name" name="province_name" value="{{ old('province_name', $job->province) }}">
                    <input type="hidden" id="district_name" name="district_name" value="">
                    <input type="hidden" id="ward_name" name="ward_name" value="{{ old('ward_name', $job->wards) }}">
                  </div>

                  <div class="col-md-6">
                    <label class="small text-muted mb-1">Xã / phường</label>
                    <select class="tw-input" id="wards" name="wards">
                      <option value="">Chọn Phường / Xã</option>
                      @if (!empty($job->wards))
                        <option value="{{ $job->wards }}" selected>{{ $job->wards }}</option>
                      @endif
                    </select>
                  </div>
                </div>
                <div class="row g-2 mb-2">
                  <div class="col-md-12">
                    <label class="small text-muted mb-1">Địa chỉ cụ thể (Tòa nhà, số nhà, đường...)</label>
                    <input type="text" class="tw-input" id="location_detail" name="location_detail"
                      placeholder="Địa chỉ cụ thể (Tòa nhà, số nhà, đường...)"
                      value="{{ old('location_detail', $job->location_detail) }}">
                  </div>
                </div>
              </div>

              <div class="tw-form-group">
                <label for="company_website">Website công ty</label>
                <input type="text" placeholder="https://topwork.vn" id="company_website"
                  name="company_website" class="tw-input" value="{{ $job->company_website }}">
              </div>

              <div class="pt-4 border-top mt-4">
                <button type="submit" class="tw-btn tw-btn-primary">
                  Cập nhật tin tuyển dụng
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
    $("#editJobForm").submit(function(e) {
      e.preventDefault();
      $("button[type=submit]").prop("disabled", true);
      $.ajax({
        url: '{{ route('account.updateJob', $job->id) }}',
        type: 'POST',
        dataType: 'json',
        data: $("#editJobForm").serializeArray(),
        success: function(response) {
          $("button[type=submit]").prop("disabled", false);
          if (response.status == true) {
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
      var currentWard = "{{ old('ward_name', $job->wards) }}";

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
