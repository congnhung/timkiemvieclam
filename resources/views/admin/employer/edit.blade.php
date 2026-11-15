@extends('admin.admin_master')
@section('content')
  <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
    <div class="flex-grow-1">
      <h4 class="fs-18 fw-semibold mb-0">Quản lý nhà tuyển dụng</h4>
    </div>
    <div class="row row-cols-lg-auto g-2 align-items-center">
      <div class="col">
        <a href="{{ route('admin.employer') }}" class="btn btn-success">Nhà tuyển dụng</a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-4">
      <div class="card">
        <div class="d-flex card-header justify-content-between align-items-center">
          <h4 class="header-title">Cập nhật</h4>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive-sm">
          @if ($employer->status == 0)
            <div class="alert alert-warning py-2.5 px-3 mb-3 border border-warning-subtle" style="font-size: 13.5px;">
              <div class="d-flex align-items-center gap-2">
                <i class="ti ti-clock fs-20 text-warning"></i>
                <div>
                  <strong>Tài khoản đang chờ duyệt!</strong> Hãy kiểm tra thông tin hợp lệ, sau đó đổi <strong>Trạng thái</strong> sang <strong>"Hoạt động"</strong> và bấm <strong>Xử lý</strong> để Nhà tuyển dụng có thể đăng nhập.
                </div>
              </div>
            </div>
          @endif
          <form class="needs-validation" method="POST" action="{{ route('admin.update.employer', $employer->id) }}">
            @csrf
            <div class="mb-3">
              <label class="form-label" for="validationCustom01">Họ và tên</label>
              <input type="text"
                class="form-control @error('fullname')
                  is-invalid
              @enderror"
                name="fullname" value="{{ $errors->has('fullname') ? '' : old('fullname', $employer->fullname) }}">
              @if ($errors->has('fullname'))
                <span class="invalid-feedback">{{ $errors->first('fullname') }}</span>
              @endif
            </div>
            <div class="mb-3">
              <label class="form-label" for="validationCustom01">Email</label>
              <input type="text"
                class="form-control @error('email')
                  is-invalid
              @enderror" name="email"
                value="{{ $errors->has('email') ? '' : old('email', $employer->email) }}">
              @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
              @endif
            </div>
            <div class="mb-3">
              <label class="form-label" for="validationCustom01">Số điện thoại</label>
              <input type="text"
                class="form-control @error('mobile')
                  is-invalid
              @enderror" name="mobile"
                value="{{ $errors->has('mobile') ? '' : old('mobile', $employer->mobile) }}">
              @if ($errors->has('mobile'))
                <span class="invalid-feedback">{{ $errors->first('mobile') }}</span>
              @endif
            </div>
            <div class="mb-3">
              <label class="form-label" for="validationCustom02">Trạng thái</label>
              <select class="form-select @error('status')
                  is-invalid
              @enderror"
                name="status">
                <option value="0" {{ $employer->status == 0 ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="1" {{ $employer->status == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="2" {{ $employer->status == 2 ? 'selected' : '' }}>Đã khóa</option>
              </select>
              <span class="invalid-feedback">{{ $errors->first('status') }}</span>
            </div>
            <button class="btn btn-primary" type="submit">Xử lý</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-xl-8">
      <div class="card">
        <div class="d-flex card-header justify-content-between align-items-center">
          <h4 class="header-title">Danh sách</h4>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive-sm">
          <table class="table table-striped mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($employers as $employer)
                <tr>
                  <td>{{ $employer->id }}</td>
                  <td>{{ $employer->fullname }}</td>
                  <td>{{ $employer->email }}</td>
                  <td>{{ $employer->mobile }}</td>
                  <td>
                    @if ($employer->status == env('STATUS_ACTIVE'))
                      <span>Hoạt động</span>
                    @elseif ($employer->status == env('STATUS_INACTIVE'))
                      <span>Đã khóa</span>
                    @endif
                  </td>
                  <td class="text-muted">
                    <a href="{{ route('admin.edit.employer', $employer->id) }}" class="link-reset fs-20 p-1">
                      {!! file_get_contents(public_path('admin/icon/pencil.svg')) !!}</i></a>
                    <form id="deleteForm" class="d-inline" method="POST"
                      action="{{ route('admin.delete.employer', $employer->id) }}">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="delete link-reset fs-20 p-1 border-0 bg-transparent">
                        {!! file_get_contents(public_path('admin/icon/trash.svg')) !!}</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          {{ $employers->links() }}
        </div> <!-- end table-responsive-->
      </div>
    </div>
  </div>
  </div>
@endsection
