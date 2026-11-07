@extends('admin.admin_master')
@section('content')
  <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
    <div class="flex-grow-1">
      <h4 class="fs-18 fw-semibold mb-0">Quản lý việc làm</h4>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom border-dashed d-flex align-items-center">
          <h4 class="header-title">Cập nhật</h4>
        </div>
        <div class="card-body">
          <form class="needs-validation" method="POST" action="{{ route('admin.update.job', $job->id) }}">
            @csrf
            <div class="row g-2">
              <div class="mb-3 col-md-6">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text"
                  class="form-control @error('title')
                  is-invalid
              @enderror" id="title"
                  name="title" value="{{ $errors->has('title') ? '' : old('title', $job->title) }}">
                @if ($errors->has('title'))
                  <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                @endif
              </div>
              <div class="mb-3 col-md-6">
                <label for="company_name" class="form-label">Tên công ty</label>
                <input type="text"
                  class="form-control @error('company_name')
                    is-invalid
                @enderror"
                  id="company_name" name="company_name"
                  value="{{ $errors->has('company_name') ? '' : old('company_name', $job->company_name) }}">
                @if ($errors->has('company_name'))
                  <span class="invalid-feedback">{{ $errors->first('company_name') }}</span>
                @endif
              </div>
            </div>
            <div class="row g-2">
              <div class="mb-3 col-md-6">
                <label for="province" class="form-label">Tỉnh/TP (34 Tỉnh thành)</label>
                <select class="form-select" id="province" name="province">
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
              <div class="mb-3 col-md-6">
                <label for="wards" class="form-label">Xã/phường</label>
                <select class="form-select" id="wards" name="wards">
                  <option value="">Chọn Phường / Xã</option>
                  @if (!empty($job->wards))
                    <option value="{{ $job->wards }}" selected>{{ $job->wards }}</option>
                  @endif
                </select>
              </div>
            </div>
            <div class="row g-2">
              <div class="mb-3 col-md-12">
                <label for="location_detail" class="form-label">Địa chỉ chi tiết (Số nhà, đường...)</label>
                <input type="text"
                  class="form-control @error('location_detail')
                    is-invalid
                @enderror"
                  id="location_detail" name="location_detail"
                  placeholder="Ví dụ: Tầng 8, Tòa nhà Keangnam Landmark, Đường Phạm Hùng..."
                  value="{{ $errors->has('location_detail') ? '' : old('location_detail', $job->location_detail) }}">
                @if ($errors->has('location_detail'))
                  <span class="invalid-feedback">{{ $errors->first('location_detail') }}</span>
                @endif
              </div>
            </div>
            <div class="row g-2">
              <div class="mb-3 col-md-3">
                <label class="form-label">Nghành nghề</label>
                <select class="form-select @error('career')
                    is-invalid
                @enderror"
                  name="career" id="career">
                  <option value="">Vui lòng chọn nghành nghề</option>
                  @foreach ($careers as $career)
                    <option value="{{ $career->id }}" {{ $job->career_id == $career->id ? 'selected' : '' }}>
                      {{ $career->name }}</option>
                  @endforeach
                </select>
                @if ($errors->has('career'))
                  <span class="invalid-feedback">{{ $errors->first('career') }}</span>
                @endif
              </div>
              <div class="mb-3 col-md-3">
                <label class="form-label">Kinh nghiệm</label>
                <select class="form-select @error('experience')
                    is-invalid
                @enderror"
                  name="experience" id="experience">
                  <option value="">Vui lòng chọn kinh nghiệm</option>
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
                @if ($errors->has('experience'))
                  <span class="invalid-feedback">{{ $errors->first('experience') }}</span>
                @endif
              </div>
              <div class="mb-3 col-md-3">
                <label for="salary" class="form-label">Mức lương</label>
                <input type="text"
                  class="form-control @error('salary')
                    is-invalid
                @enderror"
                  id="salary" name="salary"
                  value="{{ $errors->has('salary') ? '' : old('salary', $job->salary) }}">
                @if ($errors->has('salary'))
                  <span class="invalid-feedback">{{ $errors->first('salary') }}</span>
                @endif
              </div>
              <div class="mb-3 col-md-3">
                <label for="keywords" class="form-label">Từ khóa</label>
                <input type="text" class="form-control" id="keywords" name="keywords"
                  value="{{ $job->keywords }}">
              </div>
            </div>
            <div class="row g-2">
              <div class="mb-3 col-md-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-select" name="status">
                  <option value="0" {{ $job->status == env('STATUS_PENDING') ? 'selected' : '' }}>Chờ duyệt
                  </option>
                  <option value="1" {{ $job->status == env('STATUS_APPROVED') ? 'selected' : '' }}>Đã duyệt
                  </option>
                  <option value="2" {{ $job->status == env('STATUS_LOCKED') ? 'selected' : '' }}>Đã khóa</option>
                </select>
              </div>
              <div class="mb-3 col-md-3">
                <label class="form-label">Loại hình làm việc</label>
                <select class="form-select @error('job_type')
                    is-invalid
                @enderror"
                  name="job_type" id="job_type">
                  <option value="">Vui lòng chọn loại hình làm việc</option>
                  @foreach ($jobTypes as $jobType)
                    <option value="{{ $jobType->id }}" {{ $job->job_type_id == $jobType->id ? 'selected' : '' }}>
                      {{ $jobType->name }}</option>
                  @endforeach
                </select>
                @if ($errors->has('job_type'))
                  <span class="invalid-feedback">{{ $errors->first('job_type') }}</span>
                @endif
              </div>
              <div class="mb-3 col-md-3">
                <label class="form-label">Nổi bật</label>
                <select class="form-select" name="isFeatured" id="isFeatured">
                  <option {{ $job->isFeatured == 1 ? 'selected' : '' }} value="1">Nổi bật</option>
                  <option {{ $job->isFeatured == 0 ? 'selected' : '' }} value="0">Không nổi bật</option>
                </select>
              </div>
              <div class="mb-3 col-md-3">
                <label for="company_website" class="form-label">Website</label>
                <input type="text" class="form-control" id="company_website" name="company_website"
                  value="{{ $job->company_website }}">
              </div>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Mô tả</label>
              <textarea name="description"
                class="form-control textarea @error('description')
                    is-invalid
                @enderror"
                rows="5" id="description">{{ $errors->has('description') ? '' : old('description', $job->description) }}</textarea>
              @if ($errors->has('description'))
                <span class="invalid-feedback">{{ $errors->first('description') }}</span>
              @endif
            </div>
            <div class="mb-3">
              <label for="responsibility" class="form-label">Trách nhiệm công việc</label>
              <textarea name="responsibility"
                class="form-control textarea @error('responsibility')
                    is-invalid
                @enderror"
                rows="5" id="responsibility">{{ $errors->has('responsibility') ? '' : old('responsibility', $job->responsibility) }}</textarea>
              @if ($errors->has('responsibility'))
                <span class="invalid-feedback">{{ $errors->first('responsibility') }}</span>
              @endif
            </div>
            <div class="mb-3">
              <label for="qualifications" class="form-label">Kỹ năng & Chuyên môn</label>
              <textarea name="qualifications"
                class="form-control textarea @error('qualifications')
                    is-invalid
                @enderror"
                rows="5" id="qualifications">{{ $errors->has('qualifications') ? '' : old('qualifications', $job->qualifications) }}</textarea>
              @if ($errors->has('qualifications'))
                <span class="invalid-feedback">{{ $errors->first('qualifications') }}</span>
              @endif
            </div>
            <div class="mb-3">
              <label for="benefits" class="form-label">Phúc lợi</label>
              <textarea name="benefits"
                class="form-control textarea @error('benefits')
                    is-invalid
                @enderror"
                rows="5" id="benefits">{{ $errors->has('benefits') ? '' : old('benefits', $job->benefits) }}</textarea>
              @if ($errors->has('benefits'))
                <span class="invalid-feedback">{{ $errors->first('benefits') }}</span>
              @endif
            </div>
            <button type="submit" class="btn btn-primary">Xử lý</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection

@section('customJs')
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
@endsection
