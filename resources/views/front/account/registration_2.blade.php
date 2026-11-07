<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập- TopWork - Việc làm hàng đầu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/user/css/toastr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/user/css/registration_style.css') }}">
</head>

<body>
    <div class="form-wrapper">
        <main class="form-side">
            <a href="{{ route('home') }}" title="Logo">
                <img src="{{ asset('assets/user/images/login/logo_web.png') }}" alt="Laplace Logo" class="logo">
            </a>
            <form action="" class="my-form" name="registrationForm" id="registrationForm">
                @csrf
                <div class="form-welcome-row">
                    <h1>Đăng ký</h1>
                </div>

                <div class="text-field">
                    <label for="">Họ và tên <span style="color: red">*</span></label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="" 
                           placeholder="Ví dụ: Nguyễn Văn A">
                    <p style="color: red"></p>
                </div>
                
                <div class="text-field">
                    <label for="email">Email <span style="color: red">*</span></label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class=""
                           autocomplete="off" 
                           value="{{ old('email') }}"
                           placeholder="Ví dụ: you@example.com" 
                    >
                    <p style="color: red"></p>
                </div>
                <div class="text-field">
                    <label for="password">Mật khẩu <span style="color: red">*</span></label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           class="" 
                           placeholder="Nhập mật khẩu..." 
                    >
                    <p style="color: red"></p>
                </div>
                <div class="text-field">
                    <label for="password">Xác nhận mật khẩu <span style="color: red">*</span></label>
                    <input id="confirm_password" 
                           type="password" 
                           name="confirm_password" 
                           class=""
                           placeholder="Nhập mật khẩu..." 
                    >
                    <p style="color: red"></p>
                </div>
                <div class="text-field">
                    <label for="role">Đăng ký với tư cách</label>
                    <select name="role" id="role" class="">
                        <option value="user">Người tìm việc</option>
                        <option value="employer">Nhà tuyển dụng</option>
                    </select>
                    <p style="color: red"></p>
                </div>
                <button class="my-form__button" type="submit">
                    Đăng ký
                </button>
                <div class="my-form__actions">
                    <div class="my-form__row">
                        <span>Bạn đã có tài khoản?</span>
                        <a href="{{ route("account.login") }}" title="Reset Password">
                            Đăng nhập
                        </a>
                    </div>
                </div>
            </form>
        </main>
        <aside class="info-side">
            <div class="blockquote-wrapper">
                <h1>
                    &#128079; Chào mừng bạn đến với website tìm kiếm việc làm - <span style="color: red">TopWork</span>
                </h1>
            </div>
        </aside>
    </div>
<script src="{{ asset('assets/user/js/jquery-3.6.0.min.js') }}"></script>

<script>
    $("#registrationForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route("account.processRegistration") }}',
            type: 'post',
            data: $('#registrationForm').serializeArray(),
            dataType: 'json',
            success: function (response) {
                ['name', 'email', 'password', 'confirm_password'].forEach(function (field) {
                    $("#" + field).removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                });

                if (response.status === false) {
                    $.each(response.errors, function (field, message) {
                        $("#" + field).addClass('is-invalid')
                                      .siblings('p')
                                      .addClass('invalid-feedback')
                                      .html(message);
                    });
                } else {
                    window.location.href = '{{ route("account.login") }}';
                }
            }
        });
    });
</script>
</body>

</html>