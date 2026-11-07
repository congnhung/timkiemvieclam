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
                <option {{ $employer->status == env('STATUS_ACTIVE') ? 'selected' : '' }} value="1">Hoạt động
                </option>
                <option {{ $employer->status == env('STATUS_INACTIVE') ? 'selected' : '' }} value="0">Đã khóa
                </option>
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
