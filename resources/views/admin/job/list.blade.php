@extends('admin.admin_master')
@section('content')
  <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
    <div class="flex-grow-1">
      <h4 class="fs-18 fw-semibold mb-0">Quản lý tin việc làm</h4>
    </div>
    <form action="{{ route('admin.job') }}" class="row row-cols-lg-auto g-2 align-items-center">
      @if (request()->has('status') && request('status') !== '')
        <input type="hidden" name="status" value="{{ request('status') }}">
      @endif
      <div class="col">
        <input type="text" class="form-control" name="keyword" placeholder="Tên việc cần tìm ?"
          value="{{ Request::get('keyword') }}">
      </div>
      <div class="col">
        <input class="form-control" value="{{ Request::get('date') }}" id="example-date" type="date" name="date">
      </div>
      <div class="col">
        <button type="submit" class="btn btn-success">Tìm kiếm</button>
      </div>
      @if (request()->has('keyword') || request()->has('date') || request()->has('status'))
        <div class="col">
          <a href="{{ route('admin.job') }}" class="btn btn-outline-secondary">Xóa lọc</a>
        </div>
      @endif
    </form>
  </div>

  {{-- Tabs lọc trạng thái --}}
  <div class="row mb-3">
    <div class="col-12">
      <div class="d-flex flex-wrap gap-2 align-items-center">
        <a href="{{ route('admin.job', array_merge(request()->except('status', 'page'))) }}"
          class="btn btn-sm {{ !request()->has('status') || request('status') === '' || request('status') === 'all' ? 'btn-primary' : 'btn-light border' }}">
          Tất cả việc làm <span class="badge bg-white text-dark ms-1">{{ $allCount ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.job', array_merge(request()->except('page'), ['status' => 0])) }}"
          class="btn btn-sm {{ request('status') === '0' || request('status') === 0 ? 'btn-danger' : 'btn-outline-danger' }}">
          <i class="ti ti-clock me-1"></i> Chờ duyệt
          <span class="badge {{ request('status') === '0' || request('status') === 0 ? 'bg-white text-danger' : 'bg-danger text-white' }} ms-1">{{ $pendingCount ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.job', array_merge(request()->except('page'), ['status' => 1])) }}"
          class="btn btn-sm {{ request('status') === '1' || request('status') === 1 ? 'btn-success' : 'btn-outline-success' }}">
          <i class="ti ti-check me-1"></i> Đã duyệt
          <span class="badge {{ request('status') === '1' || request('status') === 1 ? 'bg-white text-success' : 'bg-success text-white' }} ms-1">{{ $approvedCount ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.job', array_merge(request()->except('page'), ['status' => 2])) }}"
          class="btn btn-sm {{ request('status') === '2' || request('status') === 2 ? 'btn-secondary' : 'btn-outline-secondary' }}">
          <i class="ti ti-lock me-1"></i> Đã khóa
          <span class="badge {{ request('status') === '2' || request('status') === 2 ? 'bg-white text-secondary' : 'bg-secondary text-white' }} ms-1">{{ $lockedCount ?? 0 }}</span>
        </a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="d-flex card-header justify-content-between align-items-center">
          <h4 class="header-title">Danh sách tin việc làm</h4>
          @if (($pendingCount ?? 0) > 0)
            <span class="badge bg-danger-subtle text-danger fs-12 px-2.5 py-1 rounded-pill">
              <i class="ti ti-alert-circle me-1"></i> Có {{ $pendingCount }} tin cần duyệt
            </span>
          @endif
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive-sm">
          @if ($jobs->isNotEmpty())
            <table class="table table-striped align-middle mb-0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tiêu đề</th>
                  <th>Trạng thái</th>
                  <th>Tên ngành nghề</th>
                  <th>Thời gian</th>
                  <th class="text-center">Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($jobs as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                      <a href="{{ route('admin.edit.job', $item->id) }}" class="fw-semibold text-dark text-decoration-none hover-primary">
                        {{ $item->title }}
                      </a>
                    </td>
                    <td>
                      @if ($item->status == env('STATUS_APPROVED'))
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="ti ti-check me-1"></i>Đã duyệt</span>
                      @elseif ($item->status == env('STATUS_PENDING'))
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fw-bold"><i class="ti ti-clock me-1"></i>Chờ duyệt</span>
                      @elseif ($item->status == env('STATUS_LOCKED'))
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1"><i class="ti ti-lock me-1"></i>Đã khóa</span>
                      @endif
                    </td>
                    <td>{{ $item->career->name ?? 'Chưa phân loại' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                    <td class="text-muted text-center">
                      <a href="{{ route('admin.edit.job', $item->id) }}" class="link-reset fs-20 p-1 text-primary" title="Xem chi tiết và duyệt tin">
                        {!! file_get_contents(public_path('admin/icon/pencil.svg')) !!}
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="mt-3">
              {{ $jobs->links() }}
            </div>
          @else
            <div class="text-center py-4">
              <i class="ti ti-folder-off fs-36 text-muted"></i>
              <p class="text-muted mt-2">Không tìm thấy dữ liệu phù hợp.</p>
            </div>
          @endif
        </div> <!-- end table-responsive-->
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
  </div>
@endsection
