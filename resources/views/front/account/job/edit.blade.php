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

              <!-- AI JD GENERATOR CARD -->
              <div class="tw-ai-jd-card mt-4 mb-3 p-3 p-md-4 rounded-3" 
                   style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1.5px dashed #00b14f; box-shadow: 0 4px 15px rgba(0, 177, 79, 0.08);">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                  <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" 
                         style="width: 44px; height: 44px; background: linear-gradient(135deg, #00b14f 0%, #009140 100%); box-shadow: 0 4px 12px rgba(0, 177, 79, 0.3);">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                      </svg>
                    </div>
                    <div>
                      <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 15px;">
                          TopWork AI — Soạn thảo lại mô tả công việc (JD) tự động
                        </h5>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 11px;">
                          Gemini AI
                        </span>
                      </div>
                      <p class="text-muted small mb-0 mt-1" style="font-size: 12.5px; line-height: 1.4;">
                        Bấm nút để AI tự động tối ưu hóa và soạn thảo trọn gói <strong>Mô tả, Trách nhiệm, Yêu cầu và Quyền lợi</strong> theo tiêu chuẩn mới nhất.
                      </p>
                    </div>
                  </div>
                  <button type="button" id="btnGenerateAiJd" class="btn text-white fw-semibold px-3 py-2 rounded-pill d-flex align-items-center gap-2 flex-shrink-0"
                          style="background: linear-gradient(135deg, #00b14f 0%, #009140 100%); font-size: 13.5px; box-shadow: 0 4px 14px rgba(0, 177, 79, 0.3); border: none; transition: all 0.2s ease;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                      <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <span>Tạo JD bằng AI</span>
                  </button>
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

      // Đồng bộ từ khóa kỹ năng (Keywords)
      var finalKeywords = $.trim($("#keywords").val());
      if (window.keywordChoicesInstance) {
        var choiceTags = window.keywordChoicesInstance.getValue(true);
        if (Array.isArray(choiceTags) && choiceTags.length > 0) {
          finalKeywords = choiceTags.join(', ');
        }
        var pending = $.trim($(".choices__input--cloned").val());
        if (pending) {
          finalKeywords = finalKeywords ? (finalKeywords + ', ' + pending) : pending;
        }
      }
      $("#keywords").val(finalKeywords);

      var formData = $("#editJobForm").serializeArray();
      var kwField = formData.find(function(item) { return item.name === 'keywords'; });
      if (kwField) {
        kwField.value = finalKeywords;
      } else {
        formData.push({ name: 'keywords', value: finalKeywords });
      }

      $.ajax({
        url: '{{ route('account.updateJob', $job->id) }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
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
        window.keywordChoicesInstance = new Choices(keywordInput, {
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

    // Tạo / Cập nhật JD tự động bằng AI
    $(document).ready(function() {
      $("#btnGenerateAiJd").click(function(e) {
        e.preventDefault();
        var title = $.trim($("#title").val());
        if (!title) {
          if (typeof toastr !== 'undefined') {
            toastr.warning('Vui lòng nhập "Tiêu đề công việc" trước để AI soạn thảo JD chính xác!');
          } else {
            alert('Vui lòng nhập "Tiêu đề công việc" trước để AI soạn thảo JD chính xác!');
          }
          $("#title").focus().addClass('is-invalid');
          return;
        }
        $("#title").removeClass('is-invalid');

        var $btn = $(this);
        var oldHtml = $btn.html();
        $btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin me-2"></i>Gemini AI đang soạn thảo...');

        var postData = {
          title: title,
          level: $.trim($("#level").val()),
          category_id: $("#choices-single-default").val(),
          skills: $.trim($("#keywords").val()),
          _token: '{{ csrf_token() }}'
        };

        $.ajax({
          url: '{{ route('account.generateAiJd') }}',
          type: 'POST',
          dataType: 'json',
          data: postData,
          success: function(res) {
            $btn.prop("disabled", false).html(oldHtml);
            if (res.status && res.data) {
              function setEditorHtml(id, html) {
                var $el = $(id);
                if ($el.length) {
                  if ($.fn.trumbowyg && $el.data('trumbowyg')) {
                    $el.trumbowyg('html', html);
                  } else {
                    $el.val(html);
                  }
                }
              }

              if (res.data.salary && !$.trim($("#salary").val())) {
                $("#salary").val(res.data.salary);
              }

              setEditorHtml('#description', res.data.description);
              setEditorHtml('#responsibility', res.data.responsibility);
              setEditorHtml('#qualifications', res.data.qualifications);
              setEditorHtml('#benefits', res.data.benefits);

              if (res.data.keywords) {
                $("#keywords").val(res.data.keywords);
                if (window.keywordChoicesInstance) {
                  try {
                    window.keywordChoicesInstance.clearStore();
                    var tags = res.data.keywords.split(',').map(function(s) { return s.trim(); }).filter(Boolean);
                    window.keywordChoicesInstance.setValue(tags);
                  } catch (err) {
                    console.warn("Choices tag set warning:", err);
                  }
                }
              }

              if (typeof toastr !== 'undefined') {
                toastr.success('Gemini AI đã soạn thảo JD thành công! Bạn có thể xem và tinh chỉnh lại các mục bên dưới.');
              }

              $('html, body').animate({
                scrollTop: $("#description").closest('.tw-form-group').offset().top - 100
              }, 400);
            } else {
              var msg = res.message || 'Không thể tạo JD vào lúc này. Vui lòng thử lại!';
              if (typeof toastr !== 'undefined') toastr.error(msg); else alert(msg);
            }
          },
          error: function(xhr) {
            $btn.prop("disabled", false).html(oldHtml);
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Có lỗi khi kết nối AI.';
            if (typeof toastr !== 'undefined') toastr.error(msg); else alert(msg);
          }
        });
      });
    });
  </script>
@endsection
