@extends('admin.admin_master')
@section('content')
  <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
    <div class="flex-grow-1">
      <h4 class="fs-18 fw-semibold mb-0">Quản lý ứng tuyển</h4>
    </div>
    <form action="{{ route('admin.apply.job') }}" class="row row-cols-lg-auto g-2 align-items-center">
      <div class="col">
        <input type="text" class="form-control" name="keyword" placeholder="Ứng viên ?"
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
          @if ($jobApply->isNotEmpty() && !session('noti'))
            <table class="table table-striped mb-0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Công việc</th>
                  <th>CV</th>
                  <th>Ứng viên</th>
                  <th>Nhà tuyển dụng</th>
                  <th>Hình thức làm việc</th>
                  <th>Ngày ứng tuyển</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($jobApply as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->job->title }}</td>
                    <td><a href="{{ route('download', $item->cv_path) }}">{!! file_get_contents(public_path('admin/icon/file-download.svg')) !!}</a></td>
                    <td>{{ $item->user->fullname }}</td>
                    <td>{{ $item->employer->fullname }}</td>
                    <td>{{ $item->job->jobType->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->applied_date)->format('d/m/Y') }}</td>
                    <td>
                      @if ($item->status == env('STATUS_PENDING'))
                        <span>Chờ duyệt</span>
                      @else
                        <span>Đã duyệt</span>
                      @endif
                    </td>
                    <td class="text-muted">
                      <form id="deleteForm" class="d-inline" method="POST"
                        action="{{ route('admin.delete.applyjob', $item->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="link-reset fs-20 p-1 border-0 bg-transparent delete">
                          {!! file_get_contents(public_path('admin/icon/trash.svg')) !!}</i></button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {{ $jobApply->links() }}
          @else
            @if (session('noti'))
              <div class="text-center">
                <span>{{ session('noti') }}</span>
              </div>
            @else
              <div class="text-center">
                <span>Không có dữ liệu</span>
              </div>
            @endif
          @endif
        </div> <!-- end table-responsive-->
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
  </div>
@endsection
