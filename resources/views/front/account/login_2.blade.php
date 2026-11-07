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
    <link rel="stylesheet" href="{{ asset('assets/user/css/login_style.css') }}">
</head>

<body>
    <div class="form-wrapper">
        <main class="form-side">
            <a href="{{ route('home') }}" title="Logo">
                <img src="{{ asset('assets/user/images/login/logo_web.png') }}" alt="Laplace Logo" class="logo">
            </a>
            <form class="my-form" action="{{ route('account.authenticate') }}" method="post">
                @csrf
                <div class="form-welcome-row">
                    <h1>Đăng nhập</h1>
                </div>
                <div class="socials-row">
                    <a href="{{ route('google-auth') }}" title="Use Github">
                        <img src="{{ asset('assets/user/images/login/google.png') }}" alt="Google">
                        Đăng nhập bằng Google
                    </a>
                </div>
                <div class="divider">
                    <div class="divider-line"></div>
                    Hoặc
                    <div class="divider-line"></div>
                </div>
                <div class="text-field">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="@error('email') is-invalid @enderror"
                           autocomplete="off" 
                           value="{{ old('email') }}"
                           placeholder="Ví dụ: you@example.com" 
                    >
                    @error('email')
                        <p class="invalid-feedback" style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="text-field">
                    <label for="password">Mật khẩu</label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           class="@error('password') is-invalid @enderror" 
                           placeholder="Nhập mật khẩu..." 
                    >
                    @error('password')
                        <p class="invalid-feedback" style="color: red">{{ $message }}</p>
                    @enderror
                </div>
                <button class="my-form__button" type="submit">
                    Đăng nhập
                </button>
                <p class="forgotPass">
                    <a href="{{ route("account.forgotPassword") }}" 
                        class="linkForgotPass">Quên mật khẩu?
                    </a>
                </p>
                <div class="my-form__actions">
                    <div class="my-form__row">
                        <span>Bạn chưa có tài khoản?</span>
                        <a href="{{ route("account.registration") }}" title="Reset Password">
                            Đăng ký ngay
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
    
    
</body>

</html>