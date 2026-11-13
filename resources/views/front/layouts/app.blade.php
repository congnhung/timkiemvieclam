<!DOCTYPE html>
<html class="no-js" lang="vi" />

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>TopWork - Việc làm hàng đầu</title>
  <meta name="description" content="" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />
  <meta name="HandheldFriendly" content="True" />
  <meta name="pinterest" content="nopin" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- Google Fonts: Lexend (Lexend-Bold, Regular, SemiBold, Medium) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/user/css/font-awesome.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/user/trumbowyg/trumbowyg.min.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/user/css/style.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/user/css/message.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/user/choices/public/assets/styles/choices.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user/css/toastr.min.css') }}" />
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/user/images/logo_web.jpg') }}" />
  <link href="{{ asset('assets/user/css/select2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/user/css/topcv_theme.css') }}" />
  @include('front.layouts.design_system')
</head>

<body data-instant-intensity="mousedown">

  @include('front.layouts.header')

  @yield('main')
  @if (Auth::check() && Auth::user()->role === 'user')
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title pb-0" id="exampleModalLabel">Đổi ảnh đại diện</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="profilePicForm" name="profilePicForm" action="" method="post">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Chọn ảnh đại diện</label>
                <input type="file" class="form-control" id="image" name="image">
                <p class="text-danger" id="image-error"></p>
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
  @endif

  @if (Auth::check() && Auth::user()->role === 'employer')
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title pb-0" id="exampleModalLabel">Đổi ảnh logo công ty</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="profilePicForm" name="profilePicForm" action="" method="post">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Chọn logo</label>
                <input type="file" class="form-control" id="image" name="image">
                <p class="text-danger" id="image-error"></p>
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
  @endif

  @include('front.layouts.footer')


  <script src="{{ asset('assets/user/js/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('assets/user/js/jquery.js') }}"></script>
  <script src="{{ asset('assets/user/js/select2.min.js') }}"></script>
  <script src="{{ asset('assets/user/js/sweetalert.js') }}"></script>
  <script src="{{ asset('assets/user/choices/public/assets/scripts/choices.min.js') }}"></script>
  <script src="{{ asset('assets/user/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
  {{-- <script src="{{ asset('admin/vendor/bootstrap/bootstrap.js') }}"></script> --}}
  <script src="{{ asset('assets/user/js/instantpages.5.1.0.min.js') }}"></script>
  <script src="{{ asset('assets/user/js/lazyload.17.6.0.min.js') }}"></script>
  <script src="{{ asset('assets/user/js/custom.js') }}"></script>
  <script src="{{ asset('assets/user/js/datepicker.js') }}"></script>
  <script src="{{ asset('assets/user/trumbowyg/trumbowyg.min.js') }}"></script>
  <script src="{{ asset('assets/user/js/toastr.min.js') }}"></script>

  @if (session()->has('toastr'))
    <script>
      toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "6000"
      };

      @foreach (session('toastr') as $type => $message)
        toastr.{{ $type }}('{{ $message }}');
      @endforeach
    </script>
  @endif

  <script>
    $('.textarea').trumbowyg();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $("#profilePicForm").submit(function(e) {
      e.preventDefault();

      var submitBtn = $(this).find('button[type="submit"]');
      submitBtn.prop('disabled', true).text('Đang tải lên...');
      $("#image-error").html('');

      var formData = new FormData(this);

      $.ajax({
        url: '{{ route('account.updateProfilePicture') }}',
        type: 'post',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(response) {
          submitBtn.prop('disabled', false).text('Cập nhật');
          if (response.status == false) {
            var errors = response.errors;
            if (errors.image) {
              $("#image-error").html(errors.image);
            }
          } else {
            window.location.reload();
          }
        },
        error: function(xhr) {
          submitBtn.prop('disabled', false).text('Cập nhật');
          console.error(xhr);
          $("#image-error").html('Có lỗi xảy ra khi xử lý ảnh, vui lòng thử lại!');
        }
      });
    });
  </script>

  <script>
    window.addEventListener('beforeunload', function () {
      var progressBar = document.getElementById('progressBar');
      var progressContainer = document.getElementById('progressBarContainer') || document.querySelector('.tw-progress-bar-wrap');

      // Hiển thị thanh tiến trình khi trang bắt đầu chuyển
      if (progressContainer) progressContainer.style.display = 'block';
      if (progressBar) {
        progressBar.style.width = '0%';
        progressBar.setAttribute('aria-valuenow', 0);
      }

      var progress = 0;
      var interval = setInterval(function () {
        if (progress < 90) {
          progress += 2; // Tăng chiều rộng dần lên 90% khi đang chuyển trang
          if (progressBar) {
            progressBar.style.width = progress + '%';
            progressBar.setAttribute('aria-valuenow', progress);
          }
        } else {
          clearInterval(interval); // Dừng tăng khi đạt đến 90%
        }
      }, 50); // Điều chỉnh tốc độ tăng (ms)
    });

    document.addEventListener('readystatechange', function () {
      var progressBar = document.getElementById('progressBar');
      var progressContainer = document.getElementById('progressBarContainer') || document.querySelector('.tw-progress-bar-wrap');

      if (document.readyState === 'complete') {
        // Khi trang tải hoàn tất, hoàn thành thanh tiến trình
        if (progressBar) {
          progressBar.style.width = '100%';
          progressBar.setAttribute('aria-valuenow', 100);
        }
        setTimeout(function () {
          if (progressContainer) progressContainer.style.display = 'none'; // Ẩn sau khi tải xong
        }, 500); // Ẩn sau 0.5 giây
      }
    });
  </script>

  @include('front.layouts.ai_chat_widget')
  @include('front.layouts.chat_widget')

  @yield('customJs')
</body>

</html>
