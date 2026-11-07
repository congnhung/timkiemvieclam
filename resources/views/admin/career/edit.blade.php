@extends('admin.admin_master')
@section('content')
  <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
    <div class="flex-grow-1">
      <h4 class="fs-18 fw-semibold mb-0">Quản lý nghề nghiệp</h4>
    </div>
    <form action="{{ route('admin.career') }}" class="row row-cols-lg-auto g-2 align-items-center">
      <div class="col">
        <input type="text" class="form-control" name="keyword" placeholder="Tên ngành nghề cần tìm ?"
          value="{{ Request::get('keyword') }}">
      </div>
      <div class="col">
        <input class="form-control" id="example-date" type="date" name="date" lang="vi-VN"
          value="{{ Request::get('date') }}">
      </div>
      <div class="col">
        <button type="submit" class="btn btn-success">Tìm kiếm</button>
      </div>
    </form>
  </div>
  <div class="row">
    <div class="col-xl-5">
      <div class="card">
        <div class="d-flex card-header justify-content-between align-items-center">
          <h4 class="header-title">Cập nhật</h4>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive-sm">
          <form class="needs-validation" method="POST" action="{{ route('admin.postEditCareer.career', $career->id) }}">
            @csrf
            <div class="mb-3">
              <label class="form-label" for="validationCustom01">Tên ngành nghề</label>
              <input type="text" placeholder="ví dụ: công nghệ thông tin"
                class="form-control @error('name')
                  is-invalid
              @enderror" name="name"
                value="{{ $errors->has('name') ? '' : old('name', $career->name) }}">
              @if ($errors->has('name'))
                <span class="invalid-feedback">{{ $errors->first('name') }}</span>
              @endif
            </div>
            <div class="mb-3">
              <label class="form-label" for="validationCustom02">Trạng thái</label>
              <select class="form-select @error('status')
                  is-invalid
              @enderror"
                name="status">
                <option value="1" {{ $career->status == env('STATUS_ACTIVE') ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ $career->status == env('STATUS_INACTIVE') ? 'selected' : '' }}>Tạm dừng</option>
              </select>
              <span class="invalid-feedback">{{ $errors->first('status') }}</span>
            </div>
            <div class="mb-3">
              <div class="form-check">
                <input {{ $career->isPopular == env('POPULAR') ? 'checked' : '' }} type="checkbox"
                  class="form-check-input" name="isPopular" id="isPopular" value="{{ env('POPULAR') }}">
                <label class="form-check-label" for="isPopular">Phổ biến</label>
              </div>
            </div>
            <button class="btn btn-primary" type="submit">Cập nhật</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-xl-7">
      <div class="card">
        <div class="d-flex card-header justify-content-between align-items-center">
          <h4 class="header-title">Danh sách</h4>
          <a href="{{ route('admin.career') }}" class="btn btn-primary">
            {!! file_get_contents(public_path('admin/icon/plus.svg')) !!}
            Thêm mới</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive-sm">
          @if ($careers->isNotEmpty())
            <table class="table table-striped mb-0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tên ngành nghề</th>
                  <th>Trạng thái</th>
                  <th>Thời gian</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($careers as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>
                      @if ($item->status == env('STATUS_ACTIVE'))
                        <span>Hoạt động</span>
                      @elseif ($item->status == env('STATUS_INACTIVE'))
                        <span>Tạm dừng</span>
                      @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                    <td class="text-muted">
                      <a href="{{ route('admin.getEditCareer.career', ['id' => $item->id]) }}"
                        class="link-reset fs-20 p-1">
                        {!! file_get_contents(public_path('admin/icon/pencil.svg')) !!}</i></a>
                      <form id="deleteForm" class="d-inline" method="POST"
                        action="{{ route('admin.deleteCareer.career', $item->id) }}">
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
            {{ $careers->links() }}
          @else
            <div class="text-center">
              <span>Không có dữ liệu</span>
            </div>
          @endif
        </div> <!-- end table-responsive-->
      </div>
    </div>
  </div>
  </div>
@endsection
@section('customJs')
  <script>
    let url = "{{ route('admin.career') }}";
  </script>
@endsection
