@extends('admin.admin_master')
@section('content')
  <div class="row">
    <div class="col-12">
      <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
          <h4 class="fs-18 fw-semibold m-0">Tổng quan</h4>
        </div>
      </div><!-- end card header -->
    </div>
    <!--end col-->
  </div> <!-- end row-->
  <div class="row">
    <div class="col">
      <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1 text-center">
        <div class="col">
          <div class="card">
            <div class="card-body">
              <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Việc làm</h5>
              <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                <div class="user-img fs-42 flex-shrink-0">
                  <span class="avatar-title text-bg-primary rounded-circle fs-22">
                    <iconify-icon icon="solar:case-round-minimalistic-bold-duotone"></iconify-icon>
                  </span>
                </div>
                <h3 class="mb-0 fw-bold">{{ $totalJobs }}</h3>
              </div>
            </div>
          </div>
        </div><!-- end col -->

        <div class="col">
          <div class="card">
            <div class="card-body">
              <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Ứng viên</h5>
              <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                <div class="user-img fs-42 flex-shrink-0">
                  <span class="avatar-title text-bg-primary rounded-circle fs-22">
                    <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                  </span>
                </div>
                <h3 class="mb-0 fw-bold">{{ $totalUsers }}</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <div class="card-body">
              <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Nhà tuyển dụng</h5>
              <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                <div class="user-img fs-42 flex-shrink-0">
                  <span class="avatar-title text-bg-primary rounded-circle fs-22">
                    <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                  </span>
                </div>
                <h3 class="mb-0 fw-bold">{{ $totalEmployers }}</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <div class="card-body">
              <h5 class="text-muted fs-13 text-uppercase" title="Number of Orders">Công việc yêu thích</h5>
              <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                <div class="user-img fs-42 flex-shrink-0">
                  <span class="avatar-title text-bg-primary rounded-circle fs-22">
                    <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                  </span>
                </div>
                <h3 class="mb-0 fw-bold">{{ $totalSavedJobs }}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xxl-6">
          <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
              <h4 class="header-title">Ngành nghề phổ biến</h4>
              <a href="{{ route('admin.career') }}" class="btn btn-sm btn-light">Thêm mới <i
                  class="ti ti-plus ms-1"></i></a>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-custom table-centered table-sm table-nowrap table-hover mb-0">
                  <tbody>
                    @if ($isPopularCareers->isNotEmpty())
                      @foreach ($isPopularCareers as $item)
                        <tr>
                          <td>
                            <div class="d-flex align-items-center">
                              <div>
                                <span class="text-muted fs-12">Ngành nghề</span> <br />
                                <h5 class="fs-14 mt-1">{{ $item->name }}</h5>
                              </div>
                            </div>
                          </td>
                          <td>
                            <span class="text-muted fs-12">Ngày tạo</span>
                            <h5 class="fs-14 mt-1 fw-normal">
                              {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                            </h5>
                          </td>
                          <td>
                            <span class="text-muted fs-12">Ngày cập nhật</span>
                            <h5 class="fs-14 mt-1 fw-normal">
                              {{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y') }}
                            </h5>
                          </td>
                          <td>
                            <span class="text-muted fs-12">Trạng thái</span>
                            <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i>
                              @if ($item->status == env('STATUS_ACTIVE'))
                                <span>Hoạt động</span>
                              @elseif ($item->status == env('STATUS_INACTIVE'))
                                <span>Tạm dừng</span>
                              @endif
                            </h5>
                          </td>
                          <td style="width: 30px;">
                            <div class="dropdown">
                              <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <div class="text-center">
                        <tr class="text-center">
                          <td>Không có dữ liệu</td>
                        </tr>
                      </div>
                    @endif
                  </tbody>
                </table>

              </div>
            </div>
            <div class="card-footer pt-0 pb-0">
              {{ $isPopularCareers->links() }}
            </div>
          </div>
        </div>

        <div class="col-xxl-6">
          <div class="card card-h-100">
            <div class="card-header d-flex flex-wrap align-items-center gap-2 border-bottom border-dashed">
              <h4 class="header-title me-auto">Việc làm nổi bật</h4>
              <div class="d-flex gap-2 justify-content-end text-end">
                <a href="{{ route('admin.job') }}" class="btn btn-sm btn-light">Danh sách việc làm <i
                    class="ti ti-download ms-1"></i></a>
                {{-- <a href="javascript:void(0);" class="btn btn-sm btn-primary">Export <i
                    class="ti ti-file-export ms-1"></i></a> --}}
              </div>
            </div>

            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-custom align-middle table-nowrap table-hover mb-0">
                  <tbody>
                    @if ($featuredJobs->isNotEmpty())
                      @foreach ($featuredJobs as $item)
                        <tr>
                          <td>
                            <div class="d-flex align-items-center">
                              <div>
                                <span class="text-muted fs-12">Công việc</span> <br>
                                <h5 class="fs-14 mt-1">{{ $item->title }}</h5>
                              </div>
                            </div>
                          </td>
                          <td>
                            <span class="text-muted fs-12">Ngàn nghề</span>
                            <h5 class="fs-14 mt-1 fw-normal">
                              {{ $item->career->name }}
                            </h5>
                          </td>
                          <td>
                            <span class="text-muted fs-12">Trạng thái</span>
                            <h5 class="fs-14 mt-1 fw-normal"><i class="ti ti-circle-filled fs-12 text-success"></i>
                              <span>
                                @if ($item->status == env('STATUS_APPROVED'))
                                  <span>Đã duyệt</span>
                                @elseif ($item->status == env('STATUS_PENDING'))
                                  <span>Chờ duyệt</span>
                                @elseif ($item->status == env('STATUS_LOCKED'))
                                  <span>Đã khóa</span>
                                @endif
                              </span>
                            </h5>
                          </td>
                          <td style="width: 30px;">
                            <div class="dropdown">
                              <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">Refresh Report</a>
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <div class="text-center">
                        <tr class="text-center">
                          <td>Không có dữ liệu</td>
                        </tr>
                      </div>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer pt-0 pb-0">
              {{ $featuredJobs->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
