{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quên mật khẩu</title>
</head>
<body>
    <h1>Xin chào {{ $mailData['user']->fullname }}</h1>
    <p>Nhấn vào đây để đổi mật khẩu</p>
        <a href="{{ route("account.resetPassword",$mailData['token']) }}">Lấy lại mật khẩu</a>
</body>
</html> --}}


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Khôi phục mật khẩu - JobFinder</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 30px;
        }

        .logo {
            max-width: 180px;
            margin-bottom: 15px;
        }

        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin: 0 0 15px 0;
        }

        .content {
            margin-bottom: 30px;
        }

        p {
            margin: 0 0 15px 0;
            font-size: 16px;
        }

        .button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #2980b9;
        }

        .footer {
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .note {
            font-size: 13px;
            color: #7f8c8d;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <img style="width: 100px;" src="{{ asset('assets/user/images/logo_web.jpg') }}" alt="JobFinder Logo" class="logo">
            <h1>Khôi Phục Mật Khẩu</h1>
        </div>

        <div class="content">
            <p>Xin chào <strong>{{ $mailData['user']->fullname }}</strong>,</p>

            <p>Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn trên <strong>JobFinder</strong>. Để
                tiếp tục quá trình, vui lòng nhấn vào nút bên dưới:</p>

            <div style="text-align: center;">
                <a href="{{ route('account.resetPassword', $mailData['token']) }}" class="button">Đặt Lại Mật Khẩu</a>
            </div>

            <p class="note">Lưu ý: Liên kết này chỉ có hiệu lực trong vòng 24 giờ. Nếu bạn không yêu cầu đặt lại mật
                khẩu, vui lòng bỏ qua email này hoặc liên hệ với bộ phận hỗ trợ.</p>
        </div>

        <div class="footer">
            <p>© 2025 TOPJOB. Tất cả các quyền được bảo lưu.</p>
            <p>Hệ thống tìm kiếm việc làm hàng đầu Việt Nam</p>
            <p>Email: chamsockhachhang.contact | Hotline: 1900 1234</p>
        </div>
    </div>
</body>

</html>
