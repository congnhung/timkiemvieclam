<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản - TopWork - Việc làm hàng đầu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/user/css/toastr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/user/css/registration_style.css') }}">
    <style>
        *, html, body, button, input, select, textarea, h1, h2, h3, h4, h5, h6, p, span, a, label {
            font-family: 'Lexend', -apple-system, BlinkMacSystemFont, sans-serif !important;
        }
        .role-selector-wrap {
            display: flex;
            gap: 6px;
            margin: 10px 0 22px;
            background: #eef2f6;
            padding: 5px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .role-tab-btn {
            flex: 1;
            padding: 10px 14px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13.5px;
            cursor: pointer;
            transition: all 0.25s ease;
            background: transparent;
            color: #64748b;
        }
        .role-tab-btn.active {
            background: #ffffff;
            color: #0f172a;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        .pending-card {
            text-align: center;
            padding: 15px 5px;
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .pending-icon-wrap {
            width: 76px;
            height: 76px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: #fffbe6;
            border: 2px solid #ffe58f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            box-shadow: 0 4px 14px rgba(250, 173, 20, 0.2);
        }
        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            text-align: left;
            font-size: 13.5px;
            margin: 20px 0;
            line-height: 1.85;
        }
        .summary-row {
            display: flex;
            gap: 8px;
            border-bottom: 1px dashed #e2e8f0;
            padding: 6px 0;
        }
        .summary-row:last-child {
            border-bottom: none;
        }
        .summary-label {
            font-weight: 600;
            color: #334155;
            min-width: 130px;
        }
        .summary-value {
            color: #0f172a;
            word-break: break-word;
        }
    </style>
</head>

<body>
    <div class="form-wrapper">
        <main class="form-side">
            <a href="{{ route('home') }}" title="Trang chủ TopWork">
                <img src="{{ asset('assets/user/images/login/logo_web.png') }}" alt="TopWork Logo" class="logo">
            </a>

            {{-- 1. Form Đăng ký tài khoản --}}
            <div id="formSection">
                <div class="form-welcome-row">
                    <h1 id="formHeading">Đăng ký tài khoản</h1>
                    <h2 id="formSubHeading">Chọn vai trò của bạn để bắt đầu</h2>
                </div>

                {{-- Tabs chọn đối tượng: Người tìm việc / Nhà tuyển dụng --}}
                <div class="role-selector-wrap">
                    <button type="button" class="role-tab-btn" data-role="user" id="tabUser">
                        👤 Người tìm việc
                    </button>
                    <button type="button" class="role-tab-btn" data-role="employer" id="tabEmployer">
                        🏢 Nhà tuyển dụng
                    </button>
                </div>

                <form action="" class="my-form" name="registrationForm" id="registrationForm">
                    @csrf
                    <input type="hidden" name="role" id="roleInput" value="user">

                    {{-- Cụm trường riêng cho Nhà tuyển dụng / Công ty --}}
                    <div id="employerFields" style="display: none;">
                        <div class="text-field">
                            <label for="company_name">Tên công ty / Doanh nghiệp <span style="color: red">*</span></label>
                            <input type="text" 
                                   name="company_name" 
                                   id="company_name" 
                                   placeholder="Ví dụ: Công ty Cổ phần Công nghệ FPT">
                            <p style="color: red"></p>
                        </div>

                        <div class="text-field">
                            <label for="mobile">Số điện thoại liên hệ <span style="color: red">*</span></label>
                            <input type="text" 
                                   name="mobile" 
                                   id="mobile" 
                                   placeholder="Ví dụ: 0912345678 (10 số)">
                            <p style="color: red"></p>
                        </div>

                        <div class="text-field">
                            <label for="location_detail">Địa chỉ trụ sở công ty <span style="color: red">*</span></label>
                            <input type="text" 
                                   name="location_detail" 
                                   id="location_detail" 
                                   placeholder="Ví dụ: Tòa nhà FPT Tower, Số 10 Phạm Văn Bạch, Cầu Giấy, Hà Nội">
                            <p style="color: red"></p>
                        </div>

                        <div class="text-field">
                            <label for="company_website">Website công ty (nếu có)</label>
                            <input type="text" 
                                   name="company_website" 
                                   id="company_website" 
                                   placeholder="Ví dụ: https://fptsoftware.com">
                            <p style="color: red"></p>
                        </div>
                    </div>

                    {{-- Các trường chung --}}
                    <div class="text-field">
                        <label for="name" id="nameLabel">Họ và tên <span style="color: red">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               placeholder="Ví dụ: Nguyễn Văn A">
                        <p style="color: red"></p>
                    </div>
                    
                    <div class="text-field">
                        <label for="email">Email đăng nhập <span style="color: red">*</span></label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               autocomplete="off" 
                               value="{{ old('email') }}"
                               placeholder="Ví dụ: you@example.com">
                        <p style="color: red"></p>
                    </div>

                    <div class="text-field">
                        <label for="password">Mật khẩu <span style="color: red">*</span></label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               placeholder="Tối thiểu 6 ký tự, gồm hoa, số và ký tự đặc biệt...">
                        <p style="color: red"></p>
                    </div>

                    <div class="text-field">
                        <label for="confirm_password">Xác nhận mật khẩu <span style="color: red">*</span></label>
                        <input id="confirm_password" 
                               type="password" 
                               name="confirm_password" 
                               placeholder="Nhập lại mật khẩu vừa đặt...">
                        <p style="color: red"></p>
                    </div>

                    <button class="my-form__button" type="submit" id="submitBtn">
                        Đăng ký tài khoản
                    </button>

                    <div class="my-form__actions">
                        <div class="my-form__row">
                            <span>Bạn đã có tài khoản?</span>
                            <a href="{{ route('account.login') }}" title="Đến trang đăng nhập">
                                Đăng nhập ngay
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- 2. Màn hình xác nhận "Admin đang xem xét" (Hiển thị khi NTD đăng ký thành công) --}}
            <div id="pendingReviewCard" class="pending-card" style="display: none;">
                <div class="pending-icon-wrap">
                    ⏳
                </div>
                <h2 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
                    Đăng ký tài khoản thành công!
                </h2>
                <div style="display: inline-block; background: #fff8e6; border: 1px solid #ffd591; color: #d46b08; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-bottom: 16px;">
                    Trạng thái: Đang chờ Quản trị viên xem xét
                </div>
                <p style="color: #475569; font-size: 14px; line-height: 1.6; margin-bottom: 16px;">
                    Hồ sơ đăng ký của <strong><span id="reviewCompanyName">Doanh nghiệp</span></strong> đã được gửi thành công đến Ban Quản Trị TopWork. Chúng tôi sẽ tiến hành kiểm duyệt thông tin công ty và kích hoạt tài khoản trong thời gian sớm nhất.
                </p>

                {{-- Bảng tóm tắt thông tin công ty vừa tạo --}}
                <div class="summary-box">
                    <div class="summary-row">
                        <div class="summary-label">🏢 Tên công ty:</div>
                        <div class="summary-value fw-bold" id="infoCompanyName">...</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">👤 Người phụ trách:</div>
                        <div class="summary-value" id="infoFullname">...</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">✉️ Email đăng nhập:</div>
                        <div class="summary-value" id="infoEmail">...</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">📞 Số điện thoại:</div>
                        <div class="summary-value" id="infoMobile">...</div>
                    </div>
                    <div class="summary-row">
                        <div class="summary-label">📍 Địa chỉ trụ sở:</div>
                        <div class="summary-value" id="infoAddress">...</div>
                    </div>
                    <div class="summary-row" id="infoWebsiteRow">
                        <div class="summary-label">🌐 Website:</div>
                        <div class="summary-value" id="infoWebsite">...</div>
                    </div>
                </div>

                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 10px; padding: 12px 16px; font-size: 13px; margin-bottom: 24px; text-align: left; line-height: 1.5;">
                    💡 <em>Sau khi Quản trị viên phê duyệt thành công, bạn sẽ nhận được thông báo để đăng nhập và đăng tin tuyển dụng không giới hạn.</em>
                </div>

                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('home') }}" style="padding: 11px 22px; border: 1px solid #cbd5e1; border-radius: 8px; color: #334155; text-decoration: none; font-size: 14px; font-weight: 500; background: #fff;">
                        ← Về trang chủ
                    </a>
                    <a href="{{ route('account.login') }}" style="padding: 11px 26px; background: #000; border: 1px solid #000; border-radius: 8px; color: #fff; text-decoration: none; font-size: 14px; font-weight: 600;">
                        Đến trang Đăng nhập →
                    </a>
                </div>
            </div>
        </main>

        <aside class="info-side">
            <div class="blockquote-wrapper">
                <h1 id="bannerTitle">
                    &#128079; Chào mừng bạn đến với website tìm kiếm việc làm - <span style="color: red">TopWork</span>
                </h1>
            </div>
        </aside>
    </div>

    <script src="{{ asset('assets/user/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/user/js/toastr.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const initialRole = '{{ $defaultRole ?? "" }}' || urlParams.get('role') || 'user';

            // Hàm chuyển đổi form giữa Ứng viên & Nhà tuyển dụng
            function setRole(role) {
                $('#roleInput').val(role);
                $('.role-tab-btn').removeClass('active');

                if (role === 'employer') {
                    $('#tabEmployer').addClass('active');
                    $('#employerFields').slideDown(250);
                    $('#formHeading').text('Đăng ký Nhà Tuyển Dụng');
                    $('#formSubHeading').text('Tạo tài khoản doanh nghiệp để tìm kiếm nhân tài');
                    $('#nameLabel').html('Họ và tên người đại diện <span style="color: red">*</span>');
                    $('#name').attr('placeholder', 'Ví dụ: Nguyễn Văn A (Trưởng phòng Tuyển dụng)');
                    $('#submitBtn').text('Đăng ký tài khoản Nhà Tuyển Dụng');
                    $('#bannerTitle').html('🏢 Đăng tuyển & tiếp cận hàng ngàn ứng viên tài năng cùng <span style="color: red">TopWork</span>');
                } else {
                    $('#tabUser').addClass('active');
                    $('#employerFields').slideUp(250);
                    $('#formHeading').text('Đăng ký tài khoản');
                    $('#formSubHeading').text('Tạo tài khoản để tìm việc làm phù hợp');
                    $('#nameLabel').html('Họ và tên <span style="color: red">*</span>');
                    $('#name').attr('placeholder', 'Ví dụ: Nguyễn Văn A');
                    $('#submitBtn').text('Đăng ký tài khoản');
                    $('#bannerTitle').html('&#128079; Chào mừng bạn đến với website tìm kiếm việc làm - <span style="color: red">TopWork</span>');
                }
            }

            // Kích hoạt tab ban đầu
            setRole(initialRole);

            // Bấm đổi tab
            $('.role-tab-btn').click(function () {
                const targetRole = $(this).data('role');
                setRole(targetRole);
            });

            // Xử lý gửi Form đăng ký
            $("#registrationForm").submit(function (e) {
                e.preventDefault();

                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true).text('Đang xử lý đăng ký...');

                $.ajax({
                    url: '{{ route("account.processRegistration") }}',
                    type: 'post',
                    data: $('#registrationForm').serializeArray(),
                    dataType: 'json',
                    success: function (response) {
                        submitBtn.prop('disabled', false);
                        const currentRole = $('#roleInput').val();
                        submitBtn.text(currentRole === 'employer' ? 'Đăng ký tài khoản Nhà Tuyển Dụng' : 'Đăng ký tài khoản');

                        // Xóa các thông báo lỗi cũ
                        ['name', 'email', 'password', 'confirm_password', 'company_name', 'mobile', 'location_detail', 'company_website'].forEach(function (field) {
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
                            if (response.is_employer) {
                                // Ẩn form đăng ký và hiển thị màn hình "Admin đang xem xét"
                                $('#formSection').hide();
                                
                                const data = response.data || {};
                                $('#reviewCompanyName').text(data.company_name || 'Doanh nghiệp');
                                $('#infoCompanyName').text(data.company_name || '---');
                                $('#infoFullname').text(data.fullname || '---');
                                $('#infoEmail').text(data.email || '---');
                                $('#infoMobile').text(data.mobile || '---');
                                $('#infoAddress').text(data.location_detail || '---');

                                if (data.company_website) {
                                    $('#infoWebsite').text(data.company_website);
                                    $('#infoWebsiteRow').show();
                                } else {
                                    $('#infoWebsiteRow').hide();
                                }

                                $('#pendingReviewCard').fadeIn(350);
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            } else {
                                window.location.href = '{{ route("account.login") }}';
                            }
                        }
                    },
                    error: function () {
                        submitBtn.prop('disabled', false).text('Đăng ký tài khoản');
                        alert('Có lỗi xảy ra trong quá trình đăng ký, vui lòng thử lại!');
                    }
                });
            });
        });
    </script>
</body>

</html>
