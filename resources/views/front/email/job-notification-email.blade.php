{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thông báo công việc</title>
</head>
<body>
    <h1>Xin chào {{ $mailData['employer']->fullname }}</h1>
    <p>Tiêu đề: {{ $mailData['job']->title }}</p>
    <br>
    <p>Thông tin người ứng tuyển:</p>
    <p>Họ và tên: {{ $mailData['user']->fullname }}</p>
    <p>Email: {{ $mailData['user']->email }}</p>
    <p>Số điện thoại: {{ $mailData['user']->mobile }}</p>
</body>
</html> --}}


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thông Báo Ứng Viên Mới - JobFinder</title>
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
            margin-bottom: 25px;
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
        h2 {
            color: #3498db;
            font-size: 20px;
            margin: 25px 0 15px 0;
        }
        .content {
            margin-bottom: 30px;
        }
        p {
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        .job-info {
            background-color: #f5f8fa;
            padding: 15px;
            border-left: 4px solid #3498db;
            border-radius: 4px;
            margin: 20px 0;
        }
        .candidate-info {
            background-color: #f5f8fa;
            padding: 15px;
            border-left: 4px solid #2ecc71;
            border-radius: 4px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 130px;
            color: #7f8c8d;
        }
        .info-value {
            flex: 1;
        }
        .button {
            display: inline-block;
            background-color: #2ecc71;
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
            background-color: #27ae60;
        }
        .footer {
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img style="width: 100px;" src="{{ asset('assets/user/images/logo_web.jpg') }}" alt="JobFinder Logo" class="logo">
            <h1>Thông Báo Ứng Viên Mới</h1>
        </div>
        
        <div class="content">
            <p>Xin chào <strong>{{ $mailData['employer']->fullname }}</strong>,</p>
            
            <p>Chúc mừng! Bạn vừa nhận được hồ sơ ứng tuyển mới cho vị trí đang tuyển dụng tại <strong>JobFinder</strong>.</p>
            
            <div class="job-info">
                <h2>Thông tin công việc</h2>
                <div class="info-row">
                    <div class="info-label">Tiêu đề:</div>
                    <div class="info-value">{{ $mailData['job']->title }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Mã công việc:</div>
                    <div class="info-value">JOB-{{ $mailData['job']->id }}</div>
                </div>
            </div>
            
            <div class="candidate-info">
                <h2>Thông tin ứng viên</h2>
                <div class="info-row">
                    <div class="info-label">Họ và tên:</div>
                    <div class="info-value">{{ $mailData['user']->fullname }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $mailData['user']->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Số điện thoại:</div>
                    <div class="info-value">{{ $mailData['user']->mobile }}</div>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('employer.candidate.view', ['job_id' => $mailData['job']->id, 'user_id' => $mailData['user']->id]) }}" class="button">Xem Chi Tiết Ứng Viên</a>
            </div>
            
            <p>Đăng nhập vào tài khoản nhà tuyển dụng của bạn để xem đầy đủ hồ sơ và liên hệ với ứng viên.</p>
        </div>
        
        <div class="footer">
            <p>© 2025 TOPJOB. Tất cả các quyền được bảo lưu.</p>
            <p>Hệ thống tìm kiếm việc làm hàng đầu Việt Nam</p>
            <p>Email: chamsockhachhang.contact | Hotline: 1900 1234</p>
        </div>
    </div>
</body>
</html>