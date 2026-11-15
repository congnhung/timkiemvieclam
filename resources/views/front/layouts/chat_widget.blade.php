@if (Auth::check())
<!-- FLOATING CHAT WIDGET -->
<div id="twChatWidget" class="tw-chat-widget shadow-lg" style="display: none;">
  <!-- Header -->
  <div class="tw-chat-header d-flex align-items-center justify-content-between p-3 text-white" 
       style="background: #00b14f; border-top-left-radius: 12px; border-top-right-radius: 12px;">
    <div class="d-flex align-items-center gap-2 text-truncate" style="max-width: 250px;">
      <div class="position-relative">
        <img id="chatWidgetAvatar" src="{{ asset('assets/user/images/avatar7.png') }}" alt="Avatar" 
             class="rounded-circle border border-white" style="width: 38px; height: 38px; object-fit: cover;">
        <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
      </div>
      <div class="text-truncate">
        <div id="chatWidgetName" class="fw-bold text-truncate" style="font-size: 14px;">Nhà tuyển dụng</div>
        <div id="chatWidgetJobTitle" class="text-white-50 text-truncate" style="font-size: 11px;">Đang tải...</div>
      </div>
    </div>
    <div class="d-flex align-items-center gap-1">
      <a id="chatWidgetFullLink" href="{{ route('account.messages') }}" title="Mở trang đầy đủ" class="btn btn-sm text-white p-1 d-flex align-items-center justify-content-center" style="line-height: 1; width: 28px; height: 28px; opacity: 0.9;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
          <polyline points="15 3 21 3 21 9"></polyline>
          <line x1="10" y1="14" x2="21" y2="3"></line>
        </svg>
      </a>
      <button type="button" id="chatWidgetDelete" class="btn btn-sm text-white p-1 d-flex align-items-center justify-content-center" style="line-height: 1; width: 28px; height: 28px; opacity: 0.9;" title="Xóa cuộc trò chuyện này">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        </svg>
      </button>
      <button type="button" id="chatWidgetClose" class="btn btn-sm text-white p-1 d-flex align-items-center justify-content-center" style="line-height: 1; width: 28px; height: 28px; opacity: 0.9;" title="Đóng">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>
  </div>

  <!-- Messages List -->
  <div id="chatWidgetMessages" class="tw-chat-body p-3 d-flex flex-column gap-2" 
       style="height: 320px; overflow-y: auto; background: #f8fafc; font-size: 13px;">
    <div class="text-center text-muted py-4 small" id="chatWidgetLoading">
      <i class="fa fa-spinner fa-spin me-1"></i> Đang tải tin nhắn...
    </div>
  </div>

  <!-- Footer Input -->
  <div class="tw-chat-footer p-2 bg-white border-top" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
    <form id="chatWidgetForm" class="d-flex align-items-center gap-2 m-0">
      <input type="text" id="chatWidgetInput" class="form-control form-control-sm" 
             placeholder="Nhập tin nhắn..." autocomplete="off" style="border-radius: 20px; font-size: 13px;">
      <button type="submit" id="chatWidgetBtnSend" class="btn btn-sm text-white rounded-circle d-flex align-items-center justify-content-center" 
              style="width: 34px; height: 34px; background: #00b14f; border: none; flex-shrink: 0;" title="Gửi tin nhắn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 2px;">
          <line x1="22" y1="2" x2="11" y2="13"></line>
          <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
        </svg>
      </button>
    </form>
  </div>
</div>

<style>
.tw-chat-widget,
.tw-chat-widget *:not(i):not([class*="fa"]):not(svg):not(path) {
  font-family: 'Lexend', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
  box-sizing: border-box;
}

.tw-chat-widget i.fa,
.tw-chat-widget .fa,
.tw-chat-widget [class*="fa-"] {
  font-family: 'FontAwesome', FontAwesome !important;
}

.tw-chat-widget {
  position: fixed;
  bottom: 24px;
  right: 86px;
  width: 360px;
  max-width: calc(100vw - 32px);
  background: #ffffff;
  border-radius: 14px;
  z-index: 100000;
  border: 1px solid rgba(0, 0, 0, 0.08);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
  transition: all 0.25s ease;
  overflow: hidden;
}

@media (max-width: 576px) {
  .tw-chat-widget {
    right: 12px !important;
    left: 12px !important;
    bottom: 12px !important;
    width: auto !important;
    max-width: calc(100vw - 24px) !important;
    border-radius: 12px;
  }
}

.tw-chat-header {
  background: #00b14f !important;
  user-select: none;
}

#chatWidgetName {
  font-family: 'Lexend', sans-serif !important;
  font-size: 14px !important;
  font-weight: 600 !important;
  line-height: 1.3 !important;
}

#chatWidgetJobTitle {
  font-family: 'Lexend', sans-serif !important;
  font-size: 11.5px !important;
  opacity: 0.9 !important;
  line-height: 1.2 !important;
}

.tw-chat-body {
  font-family: 'Lexend', sans-serif !important;
  font-size: 13.5px !important;
}

#chatWidgetInput {
  font-family: 'Lexend', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
  font-size: 13.5px !important;
  font-weight: 400 !important;
  color: #1e293b !important;
  border: 1px solid #cbd5e1 !important;
  border-radius: 20px !important;
  padding: 7px 14px !important;
  background: #ffffff !important;
  outline: none !important;
  box-shadow: none !important;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

#chatWidgetInput:focus {
  border-color: #00b14f !important;
  box-shadow: 0 0 0 3px rgba(0, 177, 79, 0.15) !important;
}

#chatWidgetInput::placeholder {
  font-family: 'Lexend', sans-serif !important;
  color: #94a3b8 !important;
  font-size: 13px !important;
}

#chatWidgetBtnSend {
  width: 32px;
  height: 32px;
  background: #00b14f !important;
  border: none !important;
  flex-shrink: 0;
  transition: background 0.15s ease, transform 0.15s ease;
}

#chatWidgetBtnSend:hover {
  background: #009643 !important;
  transform: scale(1.05);
}

.tw-msg-bubble {
  font-family: 'Lexend', sans-serif !important;
  font-size: 13.5px !important;
  max-width: 82%;
  padding: 8px 12px;
  border-radius: 14px;
  word-wrap: break-word;
  white-space: pre-wrap;
  line-height: 1.45;
}

.tw-msg-me {
  align-self: flex-end;
  background: #00b14f;
  color: #ffffff;
  border-bottom-right-radius: 3px;
}

.tw-msg-other {
  align-self: flex-start;
  background: #ffffff;
  color: #1e293b;
  border: 1px solid #e2e8f0;
  border-bottom-left-radius: 3px;
}

.tw-msg-time {
  font-family: 'Lexend', sans-serif !important;
  font-size: 10.5px;
  margin-top: 3px;
  display: block;
}

.tw-msg-me .tw-msg-time {
  color: rgba(255, 255, 255, 0.85);
  text-align: right;
}

.tw-msg-other .tw-msg-time {
  color: #94a3b8;
}
</style>

<!-- Pusher Client JS -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>
$(document).ready(function() {
  var activeConversationId = null;
  var lastLoadedMessageId = 0;
  var pollInterval = null;
  var pusherClient = null;
  var activePusherChannel = null;

  var currentUserId = {{ Auth::id() }};
  var pusherKey = "{{ config('broadcasting.connections.pusher.key') }}";
  var pusherCluster = "{{ config('broadcasting.connections.pusher.options.cluster', 'ap1') }}";

  // Khởi tạo Pusher nếu đã cấu hình Key
  if (pusherKey && pusherKey.trim() !== '') {
    try {
      pusherClient = new Pusher(pusherKey, {
        cluster: pusherCluster,
        authEndpoint: '/broadcasting/auth',
        auth: {
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        }
      });

      // Lắng nghe kênh thông báo người dùng riêng để cập nhật số tin chưa đọc
      var userChannel = pusherClient.subscribe('private-user.' + currentUserId);
      userChannel.bind('message.sent', function(data) {
        updateUnreadBadge();
        // Nếu đang mở đúng cuộc trò chuyện này
        if (activeConversationId && activeConversationId == data.conversation_id) {
          appendMessageIfNew(data);
        }
      });
    } catch (e) {
      console.warn("Pusher initialization skipped or failed:", e);
    }
  }

  // Bấm nút "Chat với nhà tuyển dụng" hoặc "Nhắn tin cho ứng viên"
  $(document).on('click', '.btn-open-chat', function(e) {
    e.preventDefault();
    var jobId = $(this).data('job-id');
    var candidateId = $(this).data('candidate-id');
    if (!jobId) return;

    var $btn = $(this);
    var oldText = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang mở chat...');

    var postData = {
      job_id: jobId,
      _token: "{{ csrf_token() }}"
    };
    if (candidateId) {
      postData.candidate_id = candidateId;
    }

    $.ajax({
      url: "{{ route('chat.init') }}",
      type: "POST",
      data: postData,
      success: function(res) {
        $btn.prop('disabled', false).html(oldText);
        if (res.status) {
          openChatWidget(res.conversation_id, res.other_user, res.job);
        }
      },
      error: function(xhr) {
        $btn.prop('disabled', false).html(oldText);
        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Không thể kết nối trò chuyện.';
        if (typeof toastr !== 'undefined') {
          toastr.error(msg);
        } else {
          alert(msg);
        }
      }
    });
  });

  function openChatWidget(conversationId, otherUser, job) {
    activeConversationId = conversationId;
    lastLoadedMessageId = 0;

    $('#chatWidgetAvatar').attr('src', otherUser.avatar);
    $('#chatWidgetName').text(otherUser.name);
    $('#chatWidgetJobTitle').text(job ? job.title : 'Trao đổi tuyển dụng');
    $('#chatWidgetFullLink').attr('href', "{{ route('account.messages') }}?conversation_id=" + conversationId);

    // Đóng cửa sổ AI chat nếu đang mở để tránh chồng chéo
    if (!$('#aiChatWindow').hasClass('d-none')) {
      $('#aiBtnClose').trigger('click');
    }
    // Trên màn hình di động nhỏ, tạm ẩn launcher AI để không chật màn hình
    if ($(window).width() <= 576) {
      $('#aiChatWrapper').fadeOut(150);
    }

    $('#twChatWidget').fadeIn(200);
    $('#chatWidgetInput').focus();

    loadMessages(conversationId);

    // Đăng ký channel Pusher cho cuộc trò chuyện
    if (pusherClient) {
      if (activePusherChannel) {
        pusherClient.unsubscribe(activePusherChannel.name);
      }
      activePusherChannel = pusherClient.subscribe('private-chat.' + conversationId);
      activePusherChannel.bind('message.sent', function(data) {
        appendMessageIfNew(data);
      });
    }

    // Polling tự động mỗi 3 giây làm fallback mượt mà
    if (pollInterval) clearInterval(pollInterval);
    pollInterval = setInterval(function() {
      if ($('#twChatWidget').is(':visible') && activeConversationId) {
        loadMessages(activeConversationId, true);
      }
    }, 3000);
  }

  $('#chatWidgetDelete').on('click', function() {
    if (!activeConversationId) return;
    if (!confirm('Bạn có chắc chắn muốn xóa toàn bộ cuộc trò chuyện này?')) return;

    var $btn = $(this);
    $btn.prop('disabled', true);

    $.ajax({
      url: '/account/chat/conversation/delete/' + activeConversationId,
      type: 'POST',
      data: {
        _token: "{{ csrf_token() }}"
      },
      success: function(res) {
        $btn.prop('disabled', false);
        if (res.status) {
          if (typeof toastr !== 'undefined') toastr.success(res.message);
          $('#twChatWidget').fadeOut(200);
          if ($(window).width() <= 576) {
            $('#aiChatWrapper').fadeIn(150);
          }
          if (pollInterval) clearInterval(pollInterval);
          activeConversationId = null;
        }
      },
      error: function(xhr) {
        $btn.prop('disabled', false);
        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Không thể xóa cuộc trò chuyện.';
        if (typeof toastr !== 'undefined') toastr.error(msg); else alert(msg);
      }
    });
  });

  $('#chatWidgetClose').on('click', function() {
    $('#twChatWidget').fadeOut(200);
    if ($(window).width() <= 576) {
      $('#aiChatWrapper').fadeIn(150);
    }
    if (pollInterval) clearInterval(pollInterval);
    activeConversationId = null;
  });

  function loadMessages(conversationId, isBackgroundPoll) {
    if (!isBackgroundPoll) {
      $('#chatWidgetMessages').html('<div class="text-center text-muted py-4 small" id="chatWidgetLoading"><i class="fa fa-spinner fa-spin me-1"></i> Đang tải tin nhắn...</div>');
    }

    $.ajax({
      url: "/account/chat/messages/" + conversationId,
      type: "GET",
      success: function(res) {
        if (res.status && res.messages) {
          if (!isBackgroundPoll) {
            $('#chatWidgetMessages').empty();
          }
          if (res.messages.length === 0 && !isBackgroundPoll) {
            $('#chatWidgetMessages').html('<div class="text-center text-muted py-4 small">Chưa có tin nhắn nào. Hãy gửi lời chào để bắt đầu cuộc trò chuyện!</div>');
          } else {
            var hasNew = false;
            res.messages.forEach(function(msg) {
              if (msg.id > lastLoadedMessageId) {
                appendMessageToUI(msg);
                lastLoadedMessageId = Math.max(lastLoadedMessageId, msg.id);
                hasNew = true;
              }
            });
            if (hasNew || !isBackgroundPoll) {
              scrollChatToBottom();
            }
          }
        }
      }
    });
  }

  function appendMessageIfNew(msgData) {
    var msgObj = {
      id: msgData.id,
      sender_id: msgData.sender_id,
      sender_name: msgData.sender_name,
      message: msgData.message,
      is_me: (msgData.sender_id == currentUserId),
      created_at: msgData.created_at || 'Vừa xong'
    };
    if (msgObj.id > lastLoadedMessageId) {
      // Xoá empty message placeholder nếu có
      $('#chatWidgetMessages .text-muted').remove();
      appendMessageToUI(msgObj);
      lastLoadedMessageId = Math.max(lastLoadedMessageId, msgObj.id);
      scrollChatToBottom();
    }
  }

  function appendMessageToUI(msg) {
    var bubbleClass = msg.is_me ? 'tw-msg-me' : 'tw-msg-other';
    var safeText = $('<div>').text(msg.message).html();
    var timeHtml = '<span class="tw-msg-time">' + (msg.created_at || 'Vừa xong') + '</span>';

    var html = '<div class="tw-msg-bubble ' + bubbleClass + '" data-msg-id="' + msg.id + '">' 
             + safeText + timeHtml + '</div>';

    $('#chatWidgetMessages').append(html);
  }

  function scrollChatToBottom() {
    var el = document.getElementById('chatWidgetMessages');
    if (el) {
      el.scrollTop = el.scrollHeight;
    }
  }

  // Gửi tin nhắn
  $('#chatWidgetForm').on('submit', function(e) {
    e.preventDefault();
    if (!activeConversationId) return;

    var message = $('#chatWidgetInput').val().trim();
    if (!message) return;

    $('#chatWidgetInput').val('');
    $('#chatWidgetBtnSend').prop('disabled', true);

    $.ajax({
      url: "/account/chat/send/" + activeConversationId,
      type: "POST",
      data: {
        message: message,
        _token: "{{ csrf_token() }}"
      },
      success: function(res) {
        $('#chatWidgetBtnSend').prop('disabled', false);
        if (res.status && res.message) {
          appendMessageIfNew(res.message);
        }
      },
      error: function(xhr) {
        $('#chatWidgetBtnSend').prop('disabled', false);
        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Không gửi được tin nhắn.';
        if (typeof toastr !== 'undefined') toastr.error(msg);
      }
    });
  });

  // Cập nhật số tin nhắn chưa đọc
  function updateUnreadBadge() {
    $.get("{{ route('chat.unreadCount') }}", function(data) {
      if (data && data.count > 0) {
        $('.chat-unread-badge').text(data.count).show();
      } else {
        $('.chat-unread-badge').hide();
      }
    });
  }

  updateUnreadBadge();
  setInterval(updateUnreadBadge, 15000); // kiểm tra badge mỗi 15s
});
</script>
@endif
