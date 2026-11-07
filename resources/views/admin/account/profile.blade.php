@extends('admin.admin_master')
@section('content')
  <section class="section-5 bg-2">
    <div class="container py-5">
      <div class="row">
        <div class="col-lg-3">
          <div class="card border-0 shadow mb-3 p-3">
            <div class="s-body text-center mt-3">
              <img
                src="{{ $user->image ? asset('admin/upload/profile/' . $user->image) : asset('admin/images/avatar-default.png') }}"
                alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
              <h5 class="mt-3 fs-4 pb-0">{{ $user->fullname }}</h5>
              <p class="text-muted mb-1 fs-6"></p>
              <div class="d-flex justify-content-center mb-2">
                <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="btn btn-primary">Đổi
                  ảnh đại diện</button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-9">
          <div class="card border-0 shadow mb-3">
            <form id="userForm" method="POST">
              @csrf
              <div class="card-body p-4">
                {{ $errors->first('image') }}
                <div class="mb-3">
                  <label for="" class="mb-2">Họ và tên <span style="color: red">*</span></label>
                  <input name="fullname" type="text" id="fullname" placeholder="Nhập họ tên..." class="form-control"
                    value="{{ $user->fullname }}">
                  <p></p>
                </div>
                <div class="mb-3">
                  <label for="" class="mb-2">Email <span style="color: red">*</span></label>
                  <input type="text" name="email" id="email" placeholder="Nhập email..." class="form-control"
                    value="{{ $user->email }}">
                  <p></p>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
              </div>
            </form>
          </div>
          <div class="card border-0 shadow mb-3">
            <form method="POST" id="changePasswordForm" name="changePasswordForm">
              @csrf
              <div class="card-body p-4">
                <div class="mb-3">
                  <label for="" class="mb-2">Mật khẩu cũ <span style="color: red">*</span></label>
                  <input type="password" name="old_password" id="old_password" placeholder="Nhập mật khẩu cũ..."
                    class="form-control">
                  <p></p>
                </div>
                <div class="mb-3">
                  <label for="" class="mb-2">Mật khẩu mới <span style="color: red">*</span></label>
                  <input type="password" name="new_password" id="new_password" placeholder="Nhập mật khẩu mới..."
                    class="form-control">
                  <p></p>
                </div>
                <div class="mb-3">
                  <label for="" class="mb-2">Xác nhận mật khẩu <span style="color: red">*</span></label>
                  <input type="password" name="confirm_password" id="confirm_password"
                    placeholder="Nhập lại mật khẩu mới..." class="form-control">
                  <p></p>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pb-0" id="exampleModalLabel">Đổi ảnh đại diện</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="needs-validation" id="profilePicForm" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Chọn ảnh đại diện</label>
              <input type="file" class="form-control
              " id="image" name="image">
              <p class="invalid-feedback" id="image-error"></p>
            </div>
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-primary mx-3">Cập nhật</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@section('customJs')
  <script>
    $('#profilePicForm').submit(function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        url: "{{ route('admin.updateImageProfile') }}",
        type: 'post',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(response) {
          if (response.status == false) {
            var errors = response.errors;
            if (errors.image) {
              $('#image-error').html(errors.image);
            }
          } else {
            window.location.href = '{{ url()->current() }}'
          }
        }
      })
    })
  </script>
  <script>
    $('#userForm').submit(function(e) {
      e.preventDefault();
      $.ajax({
        type: "put",
        url: "{{ route('admin.updateProfile') }}",
        data: $('#userForm').serializeArray(),
        dataType: "json",
        success: function(response) {
          if (response.status == true) {
            $("#fullname").removeClass('is-invalid')
              .siblings('p')
              .removeClass('invalid-feedback')
              .html('')
            $("#email").removeClass('is-invalid')
              .siblings('p')
              .removeClass('invalid-feedback')
              .html('')
            window.location.href = '{{ route('admin.profile') }}';
          } else {
            var errors = response.errors;
            if (errors.fullname) {
              $("#fullname").addClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedback')
                .html(errors.fullname)
            } else {
              $("#fullname").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback')
                .html('')
            }
            if (errors.email) {
              $("#email").addClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedback')
                .html(errors.email)
            } else {
              $("#email").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback')
                .html('')
            }
          }
        }
      });

    })
  </script>
  <script>
    $('#changePasswordForm').submit(function(e) {
      e.preventDefault()
      $.ajax({
        type: "post",
        url: "{{ route('admin.changePassword') }}",
        data: $('#changePasswordForm').serializeArray(),
        dataType: "json",
        success: function(response) {
          if (response.status == true) {
            $("#old_password").removeClass('is-invalid')
              .siblings('p')
              .removeClass('invalid-feedback')
              .html('')
            $("#new_password").removeClass('is-invalid')
              .siblings('p')
              .removeClass('invalid-feedback')
              .html('')
            $("#confirm_password").removeClass('is-invalid')
              .siblings('p')
              .removeClass('invalid-feedback')
              .html('')
            window.location.href = '{{ route('admin.profile') }}';
          } else {
            var errors = response.errors;
            if (errors.old_password) {
              $("#old_password").addClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedback')
                .html(errors.old_password)
            } else {
              $("#old_password").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback')
                .html('')
            }
            if (errors.new_password) {
              $("#new_password").addClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedback')
                .html(errors.new_password)
            } else {
              $("#new_password").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback')
                .html('')
            }
            if (errors.confirm_password) {
              $("#confirm_password").addClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedback')
                .html(errors.confirm_password)
            } else {
              $("#confirm_password").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback')
                .html('')
            }
          }
        }
      });
    })
  </script>
@endsection
@endsection
