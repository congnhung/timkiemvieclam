@extends('front.layouts.app')

@section('main')
<style>
  /* 1. Khóa thanh cuộn trình duyệt toàn trang - không để trang ngoài bị trôi/chạy theo */
  html, body {
    height: 100% !important;
    max-height: 100vh !important;
    overflow: hidden !important;
  }
  
  /* 2. Ẩn Footer, AI Chatbot và Chat Widget nổi trên trang tin nhắn để dành trọn vẹn màn hình */
  footer.tw-footer,
  .tw-footer,
  #aiChatWrapper,
  #twChatWidget {
    display: none !important;
  }

  /* 3. Section bao ngoài khít chiều cao cửa sổ hiển thị (trừ 57px navbar) */
  .tw-messages-section {
    background: var(--c-gray-50, #f8fafc);
    height: calc(100vh - 57px) !important;
    max-height: calc(100vh - 57px) !important;
    padding: 8px 0 10px 0 !important;
    overflow: hidden !important;
    box-sizing: border-box !important;
  }

  /* 4. Card chat 2 cột */
  .tw-messages-card {
    height: 100% !important;
    border-radius: 12px;
    background: #ffffff;
    overflow: hidden !important;
    display: flex;
    flex-direction: column;
  }

  /* 5. Cột trái và phải */
  .tw-col-pane {
    height: 100% !important;
    max-height: 100% !important;
    display: flex;
    flex-direction: column;
    overflow: hidden !important;
  }

  /* 6. Header (Thanh thông tin cá nhân & tiêu đề) ĐỨNG YÊN TUYỆT ĐỐI */
  .tw-pane-header {
    flex-shrink: 0 !important;
    background: #ffffff;
    z-index: 10;
  }

  /* 7. Footer nhập tin nhắn ĐỨNG YÊN TUYỆT ĐỐI */
  .tw-pane-footer {
    flex-shrink: 0 !important;
    background: #ffffff;
    z-index: 10;
  }

  /* 8. Vùng cuộn tin nhắn & danh sách - vuốt bao nhiêu cũng được, không ảnh hưởng trang ngoài */
  .tw-pane-scroll {
    flex: 1 1 0 !important;
    min-height: 0 !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    overscroll-behavior: contain !important;
    -webkit-overflow-scrolling: touch;
  }
</style>

<section class="tw-messages-section">
  <div class="container h-100 d-flex flex-column" style="padding-bottom: 0;">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-2 flex-shrink-0">
      <ol class="breadcrumb mb-0" style="font-size: 13px;">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('account.profile') }}" class="text-decoration-none text-muted">Tài khoản</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Tin nhắn</li>
      </ol>
    </nav>

    <!-- Chat Card -->
    <div class="tw-messages-card shadow-sm border" style="flex: 1 1 0; min-height: 0;">
      <div class="row g-0 h-100 flex-nowrap" style="height: 100%;">
        
        <!-- LEFT: CONVERSATIONS LIST -->
        <div class="col-md-5 col-lg-4 border-end tw-col-pane" style="background: #fbfcfe;">
          <!-- Left Header (ĐỨNG YÊN) -->
          <div class="p-3 border-bottom d-flex align-items-center justify-content-between tw-pane-header">
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 15px;">
              <i class="fa fa-comments me-2" style="color: #00b14f;"></i>Hộp thư trao đổi
            </h5>
            <span class="badge bg-light text-dark border">{{ $conversations->count() }} cuộc trò chuyện</span>
          </div>

          <!-- Left Conversations List (CUỘN THOẢI MÁI) -->
          <div class="tw-pane-scroll">
            @if ($conversations->isNotEmpty())
              <div class="list-group list-group-flush">
                @foreach ($conversations as $conv)
                  @php
                    $isActive = ($activeConversation && $activeConversation->id == $conv->id);
                    $other = $conv->other_user;
                  @endphp
                  <a href="{{ route('account.messages', ['conversation_id' => $conv->id]) }}" 
                     class="list-group-item list-group-item-action p-3 border-0 border-bottom d-flex align-items-start gap-3 {{ $isActive ? 'bg-light' : '' }}"
                     style="{{ $isActive ? 'border-left: 3px solid #00b14f !important;' : '' }}">
                    <div class="position-relative flex-shrink-0">
                      <img src="{{ !empty($other->image) ? asset('assets/user/profile_picture/thumb/'.$other->image) : asset('assets/user/images/avatar7.png') }}" 
                           alt="{{ $other->fullname ?? 'User' }}" class="rounded-circle border" style="width: 44px; height: 44px; object-fit: cover;">
                      <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                    </div>

                    <div class="flex-grow-1 overflow-hidden">
                      <div class="d-flex justify-content-between align-items-baseline mb-1">
                        <strong class="text-dark text-truncate d-block" style="font-size: 14px;">{{ $other->fullname ?? 'Người dùng' }}</strong>
                        <small class="text-muted" style="font-size: 11px;">
                          {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : '' }}
                        </small>
                      </div>

                      @if ($conv->job)
                        <div class="text-muted text-truncate mb-1" style="font-size: 11px;">
                          <i class="fa fa-briefcase me-1"></i>{{ $conv->job->title }}
                        </div>
                      @endif

                      <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted text-truncate small d-inline-block" style="max-width: 190px; font-size: 12px;">
                          {{ $conv->latestMessage ? $conv->latestMessage->message : 'Chưa có tin nhắn' }}
                        </span>
                        @if ($conv->unread_count > 0)
                          <span class="badge rounded-pill bg-danger" style="font-size: 11px;">{{ $conv->unread_count }}</span>
                        @endif
                      </div>
                    </div>
                  </a>
                @endforeach
              </div>
            @else
              <div class="p-4 text-center text-muted">
                <i class="fa fa-commenting-o fa-3x mb-3 text-muted" style="opacity: 0.3;"></i>
                <p class="small mb-0">Bạn chưa có cuộc trò chuyện nào.<br>Khi ứng viên hoặc nhà tuyển dụng nhắn tin, danh sách sẽ hiển thị ở đây.</p>
              </div>
            @endif
          </div>
        </div>

        <!-- RIGHT: ACTIVE CHAT BOX -->
        <div class="col-md-7 col-lg-8 tw-col-pane">
          @if ($activeConversation)
            @php
              $otherUser = $activeConversation->getOtherParticipant(Auth::id());
            @endphp
            <!-- Chat Header: THANH TRÀN CÁ NHÂN (ĐỨNG YÊN TUYỆT ĐỐI) -->
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between tw-pane-header">
              <div class="d-flex align-items-center gap-3">
                <img src="{{ !empty($otherUser->image) ? asset('assets/user/profile_picture/thumb/'.$otherUser->image) : asset('assets/user/images/avatar7.png') }}" 
                     alt="{{ $otherUser->fullname ?? 'User' }}" class="rounded-circle border" style="width: 42px; height: 42px; object-fit: cover;">
                <div>
                  <h6 class="fw-bold mb-0 text-dark">{{ $otherUser->fullname ?? 'Người dùng' }}</h6>
                  @if ($activeConversation->job)
                    <small class="text-muted">
                      Vị trí: 
                      @if (Auth::user()->role === 'employer')
                        <a href="{{ route('JobDetail_employer', $activeConversation->job->id) }}" class="text-decoration-underline" style="color: #00b14f;">
                          {{ $activeConversation->job->title }}
                        </a>
                      @else
                        <a href="{{ route('jobDetail', $activeConversation->job->id) }}" class="text-decoration-underline" style="color: #00b14f;">
                          {{ $activeConversation->job->title }}
                        </a>
                      @endif
                    </small>
                  @endif
                </div>
              </div>

              <div>
                <span class="badge bg-light text-success border">
                  <i class="fa fa-circle text-success me-1" style="font-size: 8px;"></i>Trực tuyến
                </span>
              </div>
            </div>

            <!-- Messages List: PHẦN TIN NHẮN (VUỐT BAO NHIÊU CŨNG ĐƯỢC) -->
            <div id="fullChatMessages" class="tw-pane-scroll p-3" 
                 style="background: #f8fafc; display: flex; flex-direction: column; gap: 10px;">
              @forelse ($activeMessages as $msg)
                @php
                  $isMe = ($msg->sender_id == Auth::id());
                @endphp
                <div style="display: flex; flex-shrink: 0; width: 100%; align-items: flex-end; justify-content: {{ $isMe ? 'flex-end' : 'flex-start' }}; gap: 8px;" data-msg-id="{{ $msg->id }}">
                  @if (!$isMe)
                    <img src="{{ !empty($msg->sender->image) ? asset('assets/user/profile_picture/thumb/'.$msg->sender->image) : asset('assets/user/images/avatar7.png') }}" 
                         alt="" class="rounded-circle border flex-shrink-0" style="width: 30px; height: 30px; object-fit: cover;">
                  @endif

                  <div style="max-width: 65%; padding: 9px 13px; border-radius: 14px; word-break: break-word; font-size: 13.5px; line-height: 1.45; display: inline-block;
                              {{ $isMe ? 'background: #00b14f; color: white; border-bottom-right-radius: 3px;' : 'background: white; color: #1e293b; border: 1px solid #e2e8f0; border-bottom-left-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,.06);' }}">
                    <div>{{ $msg->message }}</div>
                    <small style="display: block; margin-top: 3px; text-align: right; font-size: 10px; opacity: 0.75; {{ $isMe ? 'color: rgba(255,255,255,0.85);' : 'color: #94a3b8;' }}">
                      {{ $msg->created_at->format('H:i - d/m/Y') }}
                    </small>
                  </div>
                </div>
              @empty
                <div class="text-center text-muted py-5 my-auto" id="emptyChatNotice">
                  <i class="fa fa-paper-plane-o fa-2x mb-2" style="opacity: 0.4;"></i>
                  <p class="small mb-0">Chưa có tin nhắn nào.<br>Hãy bắt đầu cuộc trò chuyện!</p>
                </div>
              @endforelse
            </div>

            <!-- Input Form: THANH NHẬP TIN NHẮN (ĐỨNG YÊN TUYỆT ĐỐI) -->
            <div class="p-3 border-top tw-pane-footer">
              <form id="fullChatForm" class="d-flex align-items-center gap-2 m-0">
                <input type="text" id="fullChatInput" class="form-control" 
                       placeholder="Nhập tin nhắn phản hồi..." autocomplete="off" style="border-radius: 20px; font-size: 14px;">
                <button type="submit" id="fullChatBtnSend" class="btn text-white rounded-pill px-4" 
                        style="background: #00b14f; font-weight: 500;">
                  <i class="fa fa-paper-plane me-1"></i> Gửi
                </button>
              </form>
            </div>
          @else
            <!-- No Conversation Selected -->
            <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-5 text-muted my-auto">
              <div class="p-4 rounded-circle bg-light mb-3">
                <i class="fa fa-comments-o fa-4x text-muted" style="opacity: 0.3;"></i>
              </div>
              <h5 class="fw-bold text-dark mb-2">Chưa chọn cuộc trò chuyện</h5>
              <p class="small mb-0" style="max-width: 380px;">Chọn một cuộc trò chuyện ở danh sách bên trái hoặc bấm "Chat với nhà tuyển dụng" từ trang tin tuyển dụng.</p>
            </div>
          @endif
        </div>

      </div>
    </div>

  </div>
</section>
@endsection

@section('customJs')
@if ($activeConversation)
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
$(document).ready(function() {
  var activeConvId = {{ $activeConversation->id }};
  var currentUserId = {{ Auth::id() }};
  var lastMsgId = {{ $activeMessages->last()->id ?? 0 }};

  function scrollFullChat() {
    var el = document.getElementById('fullChatMessages');
    if (el) el.scrollTop = el.scrollHeight;
  }
  scrollFullChat();

  // Khởi tạo Pusher WebSocket
  var pusherKey = "{{ config('broadcasting.connections.pusher.key') }}";
  var pusherCluster = "{{ config('broadcasting.connections.pusher.options.cluster', 'ap1') }}";

  if (pusherKey && pusherKey.trim() !== '') {
    try {
      var pusher = new Pusher(pusherKey, {
        cluster: pusherCluster,
        authEndpoint: '/broadcasting/auth',
        auth: { headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } }
      });

      var channel = pusher.subscribe('private-chat.' + activeConvId);
      channel.bind('message.sent', function(data) {
        appendIncomingMsg(data);
      });
    } catch(e) {
      console.warn("Pusher in full chat:", e);
    }
  }

  // Polling fallback mỗi 3 giây
  setInterval(function() {
    $.get("/account/chat/messages/" + activeConvId, function(res) {
      if (res.status && res.messages) {
        var hasNew = false;
        res.messages.forEach(function(m) {
          if (m.id > lastMsgId) {
            appendIncomingMsg(m);
            lastMsgId = Math.max(lastMsgId, m.id);
            hasNew = true;
          }
        });
        if (hasNew) scrollFullChat();
      }
    });
  }, 3000);

  function appendIncomingMsg(msg) {
    if (msg.id <= lastMsgId) return;
    lastMsgId = Math.max(lastMsgId, msg.id);

    $('#emptyChatNotice').remove();
    var isMe = (msg.sender_id == currentUserId);
    var safeText = $('<div>').text(msg.message).html();
    var flexDir = isMe ? 'flex-end' : 'flex-start';
    var bubbleStyle = isMe
      ? 'background:#00b14f;color:white;border-bottom-right-radius:3px;'
      : 'background:white;color:#1e293b;border:1px solid #e2e8f0;border-bottom-left-radius:3px;box-shadow:0 1px 3px rgba(0,0,0,.06);';
    var timeColor = isMe ? 'color:rgba(255,255,255,0.85);' : 'color:#94a3b8;';

    var html = '<div style="display:flex;flex-shrink:0;width:100%;align-items:flex-end;justify-content:' + flexDir + ';gap:8px;" data-msg-id="' + msg.id + '">';
    if (!isMe) {
      html += '<img src="' + (msg.sender_avatar || '/assets/user/images/avatar7.png') + '" class="rounded-circle border flex-shrink-0" style="width:30px;height:30px;object-fit:cover;">';
    }
    html += '<div style="max-width:65%;padding:9px 13px;border-radius:14px;word-break:break-word;font-size:13.5px;line-height:1.45;display:inline-block;' + bubbleStyle + '">'
          + '<div>' + safeText + '</div>'
          + '<small style="display:block;margin-top:3px;text-align:right;font-size:10px;opacity:0.75;' + timeColor + '">' + (msg.created_at || 'Vừa xong') + '</small>'
          + '</div></div>';

    $('#fullChatMessages').append(html);
    scrollFullChat();
  }

  // Gửi tin nhắn
  $('#fullChatForm').on('submit', function(e) {
    e.preventDefault();
    var msg = $('#fullChatInput').val().trim();
    if (!msg) return;

    $('#fullChatInput').val('');
    $('#fullChatBtnSend').prop('disabled', true);

    $.ajax({
      url: "/account/chat/send/" + activeConvId,
      type: "POST",
      data: {
        message: msg,
        _token: "{{ csrf_token() }}"
      },
      success: function(res) {
        $('#fullChatBtnSend').prop('disabled', false);
        if (res.status && res.message) {
          appendIncomingMsg(res.message);
        }
      },
      error: function(xhr) {
        $('#fullChatBtnSend').prop('disabled', false);
        var err = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Không gửi được tin nhắn.';
        if (typeof toastr !== 'undefined') toastr.error(err);
      }
    });
  });
});
</script>
@endif
@endsection

