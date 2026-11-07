@extends('admin.admin_master')
@section('content')
  <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
    <div class="flex-grow-1">
      <h4 class="fs-18 fw-semibold mb-0">Quản lý ứng viên</h4>
    </div>
    <form action="{{ route('admin.user') }}" class="row row-cols-lg-auto g-2 align-items-center">
      <div class="col">
        <input type="text" class="form-control" name="keyword" placeholder="Họ tên ?"
          value="{{ Request::get('keyword') }}">
      </div>
      <div class="col">
        <input class="form-control" value="{{ Request::get('date') }}" id="example-date" type="date" name="date">
      </div>
      <div class="col">
        <button type="submit" class="btn btn-success">Tìm kiếm</button>
      </div>
    </form>
  </div>
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header border-bottom border-dashed">
          <div class="row d-flex align-items-center">
            <h4 class="header-title">Danh sách</h4>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive-sm">
          @if ($users->isNotEmpty())
            <table class="table table-striped mb-0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Họ tên</th>
                  <th>Email</th>
                  <th>Số điện thoại</th>
                  <th>Trạng thái</th>
                  <th>Ngày tạo</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                  <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile ? $user->mobile : '...' }}</td>
                    <td>
                      @if ($user->status == env('STATUS_ACTIVE'))
                        <span>Hoạt động</span>
                      @elseif ($user->status == env('STATUS_INACTIVE'))
                        <span>Đã khóa</span>
                      @endif
                    </td>
                    <td>
                      {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                    </td>
                    <td class="text-muted">
                      <a href="{{ route('admin.edit.user', $user->id) }}" class="link-reset fs-20 p-1">
                        {!! file_get_contents(public_path('admin/icon/pencil.svg')) !!}</i></a>
                      <form id="deleteForm" class="d-inline" method="POST"
                        action="{{ route('admin.delete.user', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="link-reset fs-20 p-1 border-0 bg-transparent delete">
                          {!! file_get_contents(public_path('admin/icon/trash.svg')) !!}</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {{ $users->links() }}
          @else
            <div class="text-center">
              <span>Không có dữ liệu</span>
            </div>
          @endif
        </div> <!-- end table-responsive-->
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
  </div>
@endsection
