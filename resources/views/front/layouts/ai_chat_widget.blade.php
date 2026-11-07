<style>
  .ai-launcher {
    width: 52px !important;
    height: 52px !important;
    border-radius: 50% !important;
    padding: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #00b14f !important;
    color: #ffffff !important;
    border: none !important;
    cursor: pointer !important;
    box-shadow: 0 4px 16px rgba(0, 177, 79, 0.4) !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease !important;
  }
  .ai-launcher:hover {
    background: #009643 !important;
    transform: scale(1.08) !important;
    box-shadow: 0 6px 20px rgba(0, 177, 79, 0.55) !important;
  }
  .ai-launcher svg {
    width: 28px;
    height: 28px;
    display: block;
  }
</style>

{{-- ===== AI CHAT WIDGET ===== --}}
<div id="aiChatWrapper">
  <button type="button" id="aiChatLauncher" class="ai-launcher" title="Hỏi AI việc làm" aria-label="Hỏi AI việc làm">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <rect x="3" y="8" width="18" height="12" rx="3" fill="none" stroke="white" stroke-width="2"/>
      <circle cx="9" cy="13.5" r="1.5" fill="white"/>
      <circle cx="15" cy="13.5" r="1.5" fill="white"/>
      <path d="M9.5 17h5" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
      <line x1="12" y1="2" x2="12" y2="8" stroke="white" stroke-width="2"/>
      <circle cx="12" cy="2" r="1.5" fill="white"/>
      <line x1="1" y1="13" x2="3" y2="13" stroke="white" stroke-width="2" stroke-linecap="round"/>
      <line x1="21" y1="13" x2="23" y2="13" stroke="white" stroke-width="2" stroke-linecap="round"/>
    </svg>
  </button>

  <div id="aiChatWindow" class="ai-window d-none">
    <div class="ai-header">
      <div>
        <div class="ai-header-title">Trợ lý việc làm</div>
        <div class="ai-header-sub">Được hỗ trợ bởi Gemini AI</div>
      </div>
      <div class="ai-header-actions">
        <button type="button" id="aiBtnClear">Mới</button>
        <button type="button" id="aiBtnClose">✕</button>
      </div>
    </div>

    <div class="ai-body" id="aiChatBody">
      <div class="ai-msg ai-msg-bot">
        <div class="ai-bubble">Xin chào! Bạn cần tư vấn gì về việc làm, CV hay phỏng vấn?</div>
      </div>

      <div class="ai-chips" id="aiQuickChips">
        <button type="button" class="ai-chip" onclick="sendQuickPrompt('Tìm việc làm Lập trình viên PHP Laravel')">Tìm việc Laravel</button>
        <button type="button" class="ai-chip" onclick="sendQuickPrompt('Cách viết CV chuẩn không bị sáo rỗng?')">Viết CV hiệu quả</button>
        <button type="button" class="ai-chip" onclick="sendQuickPrompt('Câu hỏi phỏng vấn IT thường gặp và cách trả lời?')">Câu hỏi phỏng vấn</button>
        <button type="button" class="ai-chip" onclick="sendQuickPrompt('Cách đàm phán lương khi nhận offer mới?')">Đàm phán lương</button>
      </div>

      <div class="ai-msg ai-msg-bot d-none" id="aiTypingIndicator">
        <div class="ai-bubble ai-typing">
          <span></span><span></span><span></span>
        </div>
      </div>
    </div>

    <div class="ai-footer">
      <form id="aiChatForm" class="ai-form">
        <input type="text" id="aiChatInput" class="ai-input" placeholder="Nhập câu hỏi..." autocomplete="off">
        <button type="submit" class="ai-send">Gửi</button>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  var aiHistory = [];
  var lastMsgId = 0;
  var pollTimer = null;

  // Lấy hoặc tạo session_id cố định trong localStorage cho khách
  var cskhSessionId = localStorage.getItem('tw_cskh_session_id');
  if (!cskhSessionId) {
    cskhSessionId = 'cskh_' + Date.now() + '_' + Math.random().toString(36).substring(2, 10);
    localStorage.setItem('tw_cskh_session_id', cskhSessionId);
  }

  // Tải lịch sử hội thoại trước đó (nếu có)
  function loadHistory() {
    $.get('{{ route('ai.history') }}?session_id=' + cskhSessionId, function(res) {
      if (res.status && res.messages && res.messages.length > 0) {
        $('#aiQuickChips').addClass('d-none');
        res.messages.forEach(function(m) {
          lastMsgId = Math.max(lastMsgId, m.id);
          if (m.sender_type === 'user') {
            $('#aiTypingIndicator').before(
              '<div class="ai-msg ai-msg-user"><div class="ai-bubble">' + esc(m.message) + '</div></div>'
            );
          } else if (m.sender_type === 'admin') {
            $('#aiTypingIndicator').before(
              '<div class="ai-msg ai-msg-admin" style="display:flex;flex-direction:column;align-items:flex-start;margin-bottom:8px;">'
              + '<div class="ai-bubble" style="background:#00b14f;color:#fff;border-radius:12px;border-bottom-left-radius:2px;max-width:85%;padding:8px 12px;font-size:13.5px;">'
              + '<div style="font-size:11px;font-weight:bold;margin-bottom:2px;opacity:0.9;">👤 ' + esc(m.sender_name) + ' (CSKH):</div>'
              + esc(m.message)
              + '</div></div>'
            );
          } else {
            $('#aiTypingIndicator').before(
              '<div class="ai-msg ai-msg-bot"><div class="ai-bubble">' + fmt(m.message) + '</div></div>'
            );
          }
        });
        scrollBot();
      }
    });
  }
  loadHistory();

  // Bắt đầu polling lắng nghe Admin trả lời
  function startPolling() {
    if (pollTimer) clearInterval(pollTimer);
    pollTimer = setInterval(function() {
      if ($('#aiChatWindow').hasClass('d-none')) return;
      $.get('{{ route('ai.poll') }}?session_id=' + cskhSessionId + '&last_id=' + lastMsgId, function(res) {
        if (res.status && res.messages && res.messages.length > 0) {
          res.messages.forEach(function(m) {
            if (m.id > lastMsgId) {
              lastMsgId = Math.max(lastMsgId, m.id);
              $('#aiTypingIndicator').before(
                '<div class="ai-msg ai-msg-admin" style="display:flex;flex-direction:column;align-items:flex-start;margin-bottom:8px;">'
                + '<div class="ai-bubble" style="background:#00b14f;color:#fff;border-radius:12px;border-bottom-left-radius:2px;max-width:85%;padding:8px 12px;font-size:13.5px;">'
                + '<div style="font-size:11px;font-weight:bold;margin-bottom:2px;opacity:0.9;">👤 ' + esc(m.sender_name) + ' (CSKH):</div>'
                + esc(m.message)
                + '</div></div>'
              );
            }
          });
          scrollBot();
        }
      });
    }, 3000);
  }

  $('#aiChatLauncher').click(function() {
    $(this).addClass('d-none');
    $('#aiChatWindow').removeClass('d-none');
    $('#aiChatInput').focus();
    scrollBot();
    startPolling();
  });

  $('#aiBtnClose').click(function() {
    $('#aiChatWindow').addClass('d-none');
    $('#aiChatLauncher').removeClass('d-none');
  });

  $('#aiBtnClear').click(function() {
    $('#aiChatBody').find('.ai-msg-user, .ai-msg-bot:not(:first-child), .ai-msg-admin').remove();
    $('#aiQuickChips').removeClass('d-none');
    aiHistory = [];
    lastMsgId = 0;
    // Cấp phiên chat mới
    cskhSessionId = 'cskh_' + Date.now() + '_' + Math.random().toString(36).substring(2, 10);
    localStorage.setItem('tw_cskh_session_id', cskhSessionId);
  });

  $('#aiChatForm').submit(function(e) {
    e.preventDefault();
    var msg = $('#aiChatInput').val().trim();
    if (!msg) return;
    sendMsg(msg);
    $('#aiChatInput').val('');
  });

  window.sendQuickPrompt = function(text) {
    $('#aiQuickChips').addClass('d-none');
    sendMsg(text);
  };

  function sendMsg(text) {
    $('#aiTypingIndicator').before(
      '<div class="ai-msg ai-msg-user"><div class="ai-bubble">' + esc(text) + '</div></div>'
    );
    $('#aiTypingIndicator').removeClass('d-none');
    scrollBot();

    $.ajax({
      url: '{{ route('ai.chat') }}',
      type: 'POST',
      data: { 
        message: text, 
        session_id: cskhSessionId,
        history: JSON.stringify(aiHistory), 
        _token: '{{ csrf_token() }}' 
      },
      dataType: 'json',
      success: function(res) {
        $('#aiTypingIndicator').addClass('d-none');
        if (res.user_message_id) {
          lastMsgId = Math.max(lastMsgId, res.user_message_id);
        }
        if (res.ai_message_id) {
          lastMsgId = Math.max(lastMsgId, res.ai_message_id);
        }
        if (res.status && res.reply) {
          aiHistory.push({ role: 'user', text: text });
          aiHistory.push({ role: 'model', text: res.reply });
          $('#aiTypingIndicator').before(
            '<div class="ai-msg ai-msg-bot"><div class="ai-bubble">' + fmt(res.reply) + '</div></div>'
          );
        } else {
          showErr(res.reply || 'Có lỗi xảy ra, thử lại nhé!');
        }
        scrollBot();
      },
      error: function() {
        $('#aiTypingIndicator').addClass('d-none');
        showErr('Không kết nối được lúc này, thử lại sau nhé!');
        scrollBot();
      }
    });
  }

  function showErr(msg) {
    $('#aiTypingIndicator').before(
      '<div class="ai-msg ai-msg-bot"><div class="ai-bubble" style="color:#dc2626;">' + esc(msg) + '</div></div>'
    );
  }

  function fmt(text) {
    return text
      .replace(/^#{1,3}\s+(.+)$/gm, '<strong>$1</strong>')
      .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
      .replace(/\*(.*?)\*/g, '<em>$1</em>')
      .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>')
      .replace(/^[\-•]\s+(.+)$/gm, '<li>$1</li>')
      .replace(/(<li>[\s\S]*?<\/li>)/g, function(m){ return '<ul>' + m + '</ul>'; })
      .replace(/\n\n/g, '<br><br>')
      .replace(/\n/g, '<br>');
  }

  function esc(t) { var d = document.createElement('div'); d.textContent = t; return d.innerHTML; }
  function scrollBot() { var b = document.getElementById('aiChatBody'); if (b) b.scrollTop = b.scrollHeight; }
});
</script>
