@extends('admin.admin_master')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column justify-content-between mb-3">
      <div>
        <h4 class="fs-18 fw-semibold m-0">
          <i class="ti ti-messages text-primary me-2"></i>Chăm sóc khách hàng & Giám sát Trợ lý AI
        </h4>
        <p class="text-muted fs-13 mb-0">Theo dõi toàn bộ cuộc trò chuyện giữa người dùng và Gemini AI, can thiệp trả lời trực tiếp khi cần thiết.</p>
      </div>
      <div>
        <span class="badge bg-primary fs-12 px-3 py-2">
          {{ $conversations->count() }} cuộc hội thoại
        </span>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card overflow-hidden" style="height: calc(100vh - 200px); min-height: 580px;">
      <div class="row g-0 h-100">
        
        <!-- CỘT TRÁI: DANH SÁCH KHÁCH HÀNG & PHIÊN CHAT -->
        <div class="col-lg-4 col-md-5 border-end d-flex flex-column h-100" style="background: #fafbfe;">
          <div class="p-3 border-bottom bg-white d-flex align-items-center justify-content-between">
            <span class="fw-bold text-dark fs-14">Hội thoại gần đây</span>
            <span class="badge bg-light text-muted border">{{ $conversations->count() }}</span>
          </div>

          <div class="overflow-auto flex-grow-1" style="height: 0;">
            @if ($conversations->isNotEmpty())
              <div class="list-group list-group-flush">
                @foreach ($conversations as $conv)
                  @php
                    $isActive = ($activeConversation && $activeConversation->id == $conv->id);
                  @endphp
                  <a href="{{ route('admin.support.index', ['conversation_id' => $conv->id]) }}" 
                     class="list-group-item list-group-item-action p-3 border-bottom {{ $isActive ? 'bg-light' : '' }}"
                     style="{{ $isActive ? 'border-left: 3px solid #00b14f !important;' : '' }}">
                    <div class="d-flex align-items-start gap-2">
                      <div class="position-relative flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width: 40px; height: 40px; background: {{ $conv->user ? '#00b14f' : '#64748b' }}; font-size: 14px;">
                          {{ mb_substr($conv->user_name, 0, 1) }}
                        </div>
                        @if ($conv->status === 'human_takeover')
                          <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;" title="Admin tiếp quản"></span>
                        @else
                          <span class="position-absolute bottom-0 end-0 bg-info border border-white rounded-circle" style="width: 10px; height: 10px;" title="AI tự động"></span>
                        @endif
                      </div>

                      <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <strong class="text-dark text-truncate fs-13">{{ $conv->user_name }}</strong>
                          <small class="text-muted fs-11">
                            {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : '' }}
                          </small>
                        </div>

                        <div class="d-flex align-items-center gap-1 mb-1">
                          @if ($conv->status === 'human_takeover')
                            <span class="badge bg-success-subtle text-success fs-10 px-2">CSKH tiếp quản</span>
                          @elseif ($conv->status === 'resolved')
                            <span class="badge bg-secondary-subtle text-secondary fs-10 px-2">Đã giải quyết</span>
                          @else
                            <span class="badge bg-info-subtle text-info fs-10 px-2">Gemini AI trả lời</span>
                          @endif

                          @if ($conv->user)
                            <span class="badge bg-light text-dark border fs-10">Thành viên</span>
                          @else
                            <span class="badge bg-light text-muted border fs-10">Khách</span>
                          @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                          <span class="text-muted text-truncate small d-inline-block" style="max-width: 180px; font-size: 12px;">
                            {{ $conv->latestMessage ? $conv->latestMessage->message : 'Chưa có tin nhắn' }}
                          </span>
                          @if ($conv->unread_for_admin > 0)
                            <span class="badge rounded-pill bg-danger" style="font-size: 10px;">{{ $conv->unread_for_admin }} mới</span>
                          @endif
                        </div>
                      </div>
                    </div>
                  </a>
                @endforeach
              </div>
            @else
              <div class="text-center text-muted p-4 my-auto">
                <i class="ti ti-messages fs-36 mb-2 text-muted" style="opacity: 0.4;"></i>
                <p class="small mb-0">Chưa có cuộc trò chuyện nào qua Trợ lý AI.</p>
              </div>
            @endif
          </div>
        </div>

        <!-- CỘT PHẢI: KHUNG CHAT LIVE VÀ TRẢ LỜI CỦA CSKH -->
        <div class="col-lg-8 col-md-7 d-flex flex-column h-100 bg-white" style="overflow: hidden;">
          @if ($activeConversation)
            <!-- Header cuộc trò chuyện -->
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between flex-shrink-0 bg-white">
              <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                     style="width: 42px; height: 42px; background: #00b14f; font-size: 15px;">
                  {{ mb_substr($activeConversation->user_name, 0, 1) }}
                </div>
                <div>
                  <h6 class="fw-bold mb-0 text-dark fs-14">
                    {{ $activeConversation->user_name }}
                    @if ($activeConversation->user)
                      <span class="badge bg-success-subtle text-success fs-10 ms-1">{{ $activeConversation->user->email }}</span>
                    @endif
                  </h6>
                  <small class="text-muted fs-11">
                    Mã phiên: <code class="text-primary">{{ $activeConversation->session_id }}</code> | Bắt đầu: {{ $activeConversation->created_at->format('H:i - d/m/Y') }}
                  </small>
                </div>
              </div>

              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-info-subtle text-info border fs-12 py-1 px-2">
                  <i class="ti ti-robot me-1"></i>AI luôn tự động trả lời
                </span>

                <!-- Xóa cuộc hội thoại -->
                <form action="{{ route('admin.support.destroy', $activeConversation->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch sử cuộc trò chuyện này?');" class="m-0">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa hội thoại">
                    <i class="ti ti-trash"></i>
                  </button>
                </form>
              </div>
            </div>

            <!-- Vùng hiển thị toàn bộ luồng tin nhắn -->
            <div id="adminChatBody" class="overflow-auto p-3 flex-grow-1" 
                 style="background: #f8fafc; display: flex; flex-direction: column; gap: 12px; height: 0;">
              @forelse ($activeMessages as $m)
                @if ($m->sender_type === 'user')
                  <!-- Tin nhắn từ Người dùng -->
                  <div class="d-flex justify-content-start gap-2" style="max-width: 75%;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                         style="width: 32px; height: 32px; background: #64748b; font-size: 11px;">
                      Khách
                    </div>
                    <div class="bg-white p-3 rounded-3 border shadow-sm" style="font-size: 13.5px; line-height: 1.45;">
                      <div class="fw-bold text-dark mb-1 fs-12">{{ $activeConversation->user_name }}</div>
                      <div style="white-space: pre-wrap; word-break: break-word;">{{ $m->message }}</div>
                      <small class="text-muted d-block mt-1 text-end fs-10">{{ $m->created_at->format('H:i - d/m/Y') }}</small>
                    </div>
                  </div>
                @elseif ($m->sender_type === 'ai')
                  <!-- Tin nhắn do Gemini AI trả lời -->
                  <div class="d-flex justify-content-start gap-2 ms-4" style="max-width: 80%;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                         style="width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); font-size: 11px;">
                      AI
                    </div>
                    <div class="p-3 rounded-3 border shadow-sm" style="background: #f0f7ff; border-color: #dbeafe !important; font-size: 13.5px; line-height: 1.45;">
                      <div class="d-flex align-items-center gap-1 text-primary fw-bold mb-1 fs-12">
                        <span>✨ Trợ lý Gemini AI tự động</span>
                      </div>
                      <div style="white-space: pre-wrap; word-break: break-word;">{{ $m->message }}</div>
                      <small class="text-muted d-block mt-1 text-end fs-10">{{ $m->created_at->format('H:i - d/m/Y') }}</small>
                    </div>
                  </div>
                @else
                  <!-- Tin nhắn phản hồi từ Admin/CSKH -->
                  <div class="d-flex justify-content-end gap-2 ms-auto" style="max-width: 75%;">
                    <div class="p-3 rounded-3 text-white shadow-sm" style="background: #00b14f; font-size: 13.5px; line-height: 1.45;">
                      <div class="fw-bold mb-1 fs-12 text-white-50">
                        <i class="ti ti-shield-check me-1"></i>{{ $m->senderUser ? $m->senderUser->fullname : 'Chuyên viên CSKH' }}
                      </div>
                      <div style="white-space: pre-wrap; word-break: break-word;">{{ $m->message }}</div>
                      <small class="d-block mt-1 text-end fs-10" style="color: rgba(255,255,255,0.85);">{{ $m->created_at->format('H:i - d/m/Y') }}</small>
                    </div>
                  </div>
                @endif
              @empty
                <div class="text-center text-muted my-auto py-5">
                  <p class="mb-0">Chưa có tin nhắn trong phiên này.</p>
                </div>
              @endforelse
            </div>

            <!-- Form gửi phản hồi của Admin -->
            <div class="p-3 border-top flex-shrink-0 bg-white">
              <form id="adminReplyForm" class="d-flex align-items-center gap-2 m-0">
                @csrf
                <input type="text" id="adminReplyInput" class="form-control" 
                       placeholder="Nhập nội dung phản hồi cho khách hàng..." autocomplete="off" style="font-size: 14px;">
                <button type="submit" id="btnAdminSend" class="btn text-white px-4 flex-shrink-0" 
                        style="background: #00b14f; font-weight: 500;">
                  <i class="ti ti-send me-1"></i> Gửi cho khách
                </button>
              </form>
              <small class="text-muted d-block mt-1 fs-11">
                <i class="ti ti-info-circle me-1"></i>Mặc định AI luôn tự động trả lời 24/7. Bạn có thể gửi thêm phản hồi bất cứ lúc nào để tư vấn hoặc hỗ trợ khách hàng.
              </small>
            </div>
          @else
            <!-- Khi chưa chọn hội thoại -->
            <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-5 text-muted my-auto">
              <div class="p-4 rounded-circle bg-light mb-3">
                <i class="ti ti-messages fs-48 text-muted" style="opacity: 0.3;"></i>
              </div>
              <h5 class="fw-bold text-dark mb-2">Chưa chọn cuộc trò chuyện</h5>
              <p class="small mb-0" style="max-width: 380px;">Chọn một cuộc trò chuyện ở danh sách bên trái để theo dõi tin nhắn và hỗ trợ trực tiếp.</p>
            </div>
          @endif
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@section('customJs')
@if ($activeConversation)
<script>
$(document).ready(function() {
  var convId = {{ $activeConversation->id }};
  var lastMsgId = {{ $activeMessages->last()->id ?? 0 }};

  function scrollAdminChat() {
    var el = document.getElementById('adminChatBody');
    if (el) el.scrollTop = el.scrollHeight;
  }
  scrollAdminChat();

  // Polling lấy tin nhắn mới mỗi 3 giây
  setInterval(function() {
    $.get("/admin/support-chat/" + convId + "/messages?last_id=" + lastMsgId, function(res) {
      if (res.status && res.messages && res.messages.length > 0) {
        res.messages.forEach(function(m) {
          if (m.id > lastMsgId) {
            lastMsgId = Math.max(lastMsgId, m.id);
            appendAdminViewMsg(m);
          }
        });
        scrollAdminChat();
      }
    });
  }, 3000);

  function appendAdminViewMsg(m) {
    var html = '';
    var safeText = $('<div>').text(m.message).html();

    if (m.sender_type === 'user') {
      html = '<div class="d-flex justify-content-start gap-2" style="max-width: 75%;">'
           + '<div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 32px; height: 32px; background: #64748b; font-size: 11px;">Khách</div>'
           + '<div class="bg-white p-3 rounded-3 border shadow-sm" style="font-size: 13.5px; line-height: 1.45;">'
           + '<div class="fw-bold text-dark mb-1 fs-12">{{ $activeConversation->user_name }}</div>'
           + '<div style="white-space: pre-wrap; word-break: break-word;">' + safeText + '</div>'
           + '<small class="text-muted d-block mt-1 text-end fs-10">' + m.created_at + '</small>'
           + '</div></div>';
    } else if (m.sender_type === 'ai') {
      html = '<div class="d-flex justify-content-start gap-2 ms-4" style="max-width: 80%;">'
           + '<div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); font-size: 11px;">AI</div>'
           + '<div class="p-3 rounded-3 border shadow-sm" style="background: #f0f7ff; border-color: #dbeafe !important; font-size: 13.5px; line-height: 1.45;">'
           + '<div class="d-flex align-items-center gap-1 text-primary fw-bold mb-1 fs-12"><span>✨ Trợ lý Gemini AI tự động</span></div>'
           + '<div style="white-space: pre-wrap; word-break: break-word;">' + safeText + '</div>'
           + '<small class="text-muted d-block mt-1 text-end fs-10">' + m.created_at + '</small>'
           + '</div></div>';
    } else {
      html = '<div class="d-flex justify-content-end gap-2 ms-auto" style="max-width: 75%;">'
           + '<div class="p-3 rounded-3 text-white shadow-sm" style="background: #00b14f; font-size: 13.5px; line-height: 1.45;">'
           + '<div class="fw-bold mb-1 fs-12 text-white-50"><i class="ti ti-shield-check me-1"></i>' + (m.sender_name || 'CSKH') + '</div>'
           + '<div style="white-space: pre-wrap; word-break: break-word;">' + safeText + '</div>'
           + '<small class="d-block mt-1 text-end fs-10" style="color: rgba(255,255,255,0.85);">' + m.created_at + '</small>'
           + '</div></div>';
    }

    $('#adminChatBody').append(html);
  }

  // Admin gửi tin nhắn trả lời
  $('#adminReplyForm').on('submit', function(e) {
    e.preventDefault();
    var msg = $('#adminReplyInput').val().trim();
    if (!msg) return;

    $('#adminReplyInput').val('');
    $('#btnAdminSend').prop('disabled', true);

    $.ajax({
      url: "/admin/support-chat/" + convId + "/reply",
      type: "POST",
      data: {
        message: msg,
        _token: "{{ csrf_token() }}"
      },
      success: function(res) {
        $('#btnAdminSend').prop('disabled', false);
        if (res.status && res.message) {
          lastMsgId = Math.max(lastMsgId, res.message.id);
          appendAdminViewMsg(res.message);
          scrollAdminChat();
        }
      },
      error: function(xhr) {
        $('#btnAdminSend').prop('disabled', false);
        var err = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Lỗi không gửi được.';
        alert(err);
      }
    });
  });
});
</script>
@endif
@endsection

