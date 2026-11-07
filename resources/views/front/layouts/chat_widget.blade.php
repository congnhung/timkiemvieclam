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
      <a id="chatWidgetFullLink" href="{{ route('account.messages') }}" title="Mở trang đầy đủ" class="btn btn-sm text-white p-1" style="line-height: 1;">
        <i class="fa fa-external-link"></i>
      </a>
      <button type="button" id="chatWidgetClose" class="btn btn-sm text-white p-1" style="line-height: 1;" title="Đóng">
        <i class="fa fa-times"></i>
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
              style="width: 32px; height: 32px; background: #00b14f; border: none; flex-shrink: 0;">
        <i class="fa fa-paper-plane" style="font-size: 12px;"></i>
      </button>
    </form>
  </div>
</div>

<style>
.tw-chat-widget {
  position: fixed;
  bottom: 24px;
  right: 24px;
  width: 350px;
  max-width: calc(100vw - 32px);
  background: #ffffff;
  border-radius: 12px;
  z-index: 9999;
  border: 1px solid rgba(0, 0, 0, 0.08);
  font-family: inherit;
  transition: all 0.3s ease;
}
.tw-msg-bubble {
  max-width: 82%;
  padding: 8px 12px;
  border-radius: 14px;
  word-wrap: break-word;
  white-space: pre-wrap;
  line-height: 1.4;
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
  font-size: 10px;
  margin-top: 3px;
  display: block;
}
.tw-msg-me .tw-msg-time {
  color: rgba(255, 255, 255, 0.8);
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

  $('#chatWidgetClose').on('click', function() {
    $('#twChatWidget').fadeOut(200);
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
