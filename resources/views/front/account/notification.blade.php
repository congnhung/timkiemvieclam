@extends('front.layouts.app')

@section('main')
<head>
    <style>
        .welcome-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .welcome-image {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<section class="section-50">
    <div class="container" style="min-height: 80vh;">
        @if ($notifications->count())
            <h3 class="mb-5 heading-line">Thông báo - Công việc bạn đã ứng tuyển</h3>
            <div class="notification-ui_dd-content">
                @forelse($notifications as $notification)
                    <div class="notification-list notification-list--unread position-relative">
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" aria-label="Xóa thông báo này">Xóa</button>
                        </form>
    
                        <div class="notification-list_content">
                            <div class="notification-list_img">
                                <img src="{{ asset('assets/user/profile_picture/thumb/' . ($notification->employer_image ?? 'notification_empty.png')) }}" 
                                     alt="{{ $notification->employer_name ?? 'logo' }}">
                            </div>
                            <div class="notification-list_detail">
                                @if ($notification->type == 'approved')
                                    <p>Nhà tuyển dụng <span style="font-weight: 600; font-style :italic">{{ $notification->employer_name }}</span> 
                                        đã phê duyệt công việc bạn đã ứng tuyển - <span style="font-weight: 600; font-style :italic">{{ $notification->job_title }}</span></p>
                                    <p class="text-muted">{{ $notification->message }}</p>
                                @elseif ($notification->type == 'rejected')
                                    <p>Nhà tuyển dụng <span style="font-weight: 600; font-style :italic">{{ $notification->employer_name }}</span> 
                                        đã từ chối đơn xin ứng tuyển của bạn tại công việc - <span style="font-weight: 600; font-style :italic">{{ $notification->job_title }}</span></p>
                                    <p class="text-danger">{{ $notification->message }}</p>
                                @elseif ($notification->type == 'job_reached')
                                    <p>Công việc <span style="font-weight: 600; font-style :italic">{{ $notification->job_title }}</span> 
                                        đã đủ số lượng ứng tuyển. <span style="color: red">Không thể ứng tuyển thêm.</span></p>
                                    <p class="text-warning">{{ $notification->message }}</p>
                                @elseif ($notification->type == 'expired')
                                    <p>Công việc <span style="font-weight: 600; font-style :italic">{{ $notification->job_title }}</span> đã hết hạn ứng tuyển.</p>
                                @elseif ($notification->type == 'job_near_expiration')
                                    <p>Công việc <span style="font-weight: 600; font-style: italic">{{ $notification->job_title }}</span> sẽ hết hạn trong <span style="color: red">3 ngày tới!</span></p>
                                @elseif ($notification->type == 'deleted')
                                    <p>Nhà tuyển dụng <span style="font-weight: 600; font-style :italic">{{ $notification->employer_name }}</span> 
                                        đã xóa công việc <span style="font-weight: 600; font-style :italic">{{ $notification->job_title }} mà bạn đã ứng tuyển</span></p>
                                @endif
                                <p class="text-muted">
                                    <small>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            {{ $notifications->links() }}
        @else
            <div class="container welcome-container">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('assets/user/images/notification_empty.png') }}" alt="Illustration" class="welcome-image mb-4">
                        <h2 class="mb-3">Chào mừng bạn đến với phần thông báo</h2>
                        <p class="mb-4">Khi nhà tuyển dụng liên hệ với bạn, bạn sẽ thấy thông báo ở đây</p>
                        <button class="btn btn-primary mb-2"><a href="{{ route('jobs') }}" style="color: white">Tìm việc làm</a></button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('customJs')

@endsection