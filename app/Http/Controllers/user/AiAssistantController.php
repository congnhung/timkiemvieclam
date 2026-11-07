<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AiAssistantController extends Controller
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Xử lý tin nhắn gửi tới Trợ lý AI (Google Gemini + Engine dự phòng) & Đồng bộ về CSKH Admin
     */
    public function chat(Request $request)
    {
        $message = trim($request->input('message') ?? $request->json('message') ?? '');
        if (empty($message)) {
            $raw = json_decode($request->getContent(), true);
            $message = trim($raw['message'] ?? '');
        }
        if (empty($message)) {
            return response()->json([
                'status' => false,
                'reply' => 'Bạn vui lòng nhập câu hỏi hoặc yêu cầu để mình hỗ trợ nhé!'
            ]);
        }

        $sessionId = $request->input('session_id') ?? $request->header('X-Session-Id');
        if (empty($sessionId)) {
            $sessionId = 'cskh_' . time() . '_' . Str::random(12);
        }

        // Tìm hoặc tạo cuộc hội thoại CSKH
        $conversation = SupportConversation::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'user_id' => Auth::id(),
                'user_name' => Auth::check() ? Auth::user()->fullname : 'Khách truy cập',
                'user_email' => Auth::check() ? Auth::user()->email : null,
                'status' => 'ai_answering',
            ]
        );

        if (Auth::check() && empty($conversation->user_id)) {
            $conversation->update([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->fullname,
                'user_email' => Auth::user()->email,
            ]);
        }

        // Lưu tin nhắn của người dùng vào CSDL
        $userMsg = SupportMessage::create([
            'support_conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'sender_id' => Auth::id(),
            'message' => $message,
            'is_read' => false,
        ]);

        $conversation->increment('unread_for_admin');
        $conversation->update(['last_message_at' => now()]);

        $history = $request->input('history');
        if (is_string($history)) {
            $history = json_decode($history, true);
        }
        if (!is_array($history)) {
            $history = [];
        }

        // 1. Nếu đã cấu hình Google Gemini API Key -> Sử dụng Gemini AI
        $response = null;
        $source = 'builtin';
        if ($this->gemini->isConfigured()) {
            $response = $this->gemini->chatCareer($message, $history);
            if (!empty($response)) {
                $source = 'gemini';
            }
        }

        // 2. Dự phòng: Engine Tuyển dụng nội bộ (Built-in Career Engine)
        if (empty($response)) {
            $response = $this->generateRecruitmentReply($message);
            $source = 'builtin';
        }

        // Lưu phản hồi của AI vào CSDL để Admin theo dõi toàn bộ luồng trò chuyện
        $aiMsg = SupportMessage::create([
            'support_conversation_id' => $conversation->id,
            'sender_type' => 'ai',
            'message' => $response,
            'is_read' => true,
        ]);

        return response()->json([
            'status' => true,
            'source' => $source,
            'session_id' => $sessionId,
            'user_message_id' => $userMsg->id,
            'ai_message_id' => $aiMsg->id,
            'reply' => $response
        ]);
    }

    /**
     * Lắng nghe tin nhắn mới từ CSKH Admin (Polling realtime cho khách)
     */
    public function poll(Request $request)
    {
        $sessionId = $request->query('session_id');
        $lastId = (int)$request->query('last_id', 0);

        if (empty($sessionId)) {
            return response()->json(['status' => false, 'messages' => []]);
        }

        $conversation = SupportConversation::where('session_id', $sessionId)->first();
        if (!$conversation) {
            return response()->json(['status' => false, 'messages' => []]);
        }

        // Lấy tin nhắn mới chưa nhận (đặc biệt là tin do Admin gửi)
        $messages = SupportMessage::where('support_conversation_id', $conversation->id)
            ->where('id', '>', $lastId)
            ->where('sender_type', 'admin')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'sender_type' => $m->sender_type,
                    'sender_name' => $m->senderUser ? $m->senderUser->fullname : 'Chuyên viên CSKH',
                    'message' => $m->message,
                    'created_at' => $m->created_at->format('H:i - d/m/Y'),
                ];
            });

        if ($messages->isNotEmpty()) {
            $conversation->update(['unread_for_user' => 0]);
        }

        return response()->json([
            'status' => true,
            'conversation_status' => $conversation->status,
            'messages' => $messages
        ]);
    }

    /**
     * Lấy lịch sử hội thoại khi người dùng F5 hoặc mở lại widget
     */
    public function history(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (empty($sessionId)) {
            return response()->json(['status' => false, 'messages' => []]);
        }

        $conversation = SupportConversation::where('session_id', $sessionId)->first();
        if (!$conversation) {
            return response()->json(['status' => false, 'messages' => []]);
        }

        $messages = SupportMessage::where('support_conversation_id', $conversation->id)
            ->orderBy('id', 'asc')
            ->take(50)
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'sender_type' => $m->sender_type,
                    'sender_name' => $m->sender_type === 'admin' 
                        ? ($m->senderUser ? $m->senderUser->fullname : 'Chuyên viên CSKH')
                        : ($m->sender_type === 'user' ? 'Bạn' : 'Trợ lý AI'),
                    'message' => $m->message,
                    'created_at' => $m->created_at->format('H:i - d/m/Y'),
                ];
            });

        return response()->json([
            'status' => true,
            'conversation_status' => $conversation->status,
            'messages' => $messages
        ]);
    }

    /**
     * Bộ máy phản hồi nghề nghiệp thông minh tích hợp sẵn
     */
    private function generateRecruitmentReply(string $msg): string
    {
        $lower = mb_strtolower($msg, 'UTF-8');

        // 1. Trường hợp: Tìm kiếm việc làm
        if (Str::contains($lower, ['tìm việc', 'việc làm', 'tuyển dụng', 'job', 'vị trí', 'công việc', 'developer', 'bán hàng', 'kế toán', 'marketing'])) {
            $filler = ['tìm việc làm', 'tìm việc', 'việc làm', 'tuyển dụng', 'tìm cho mình', 'tìm giúp', 'có việc', 'nào không', 'ở đâu', 'cho tôi', 'vị trí'];
            $cleanKeyword = trim(str_ireplace($filler, '', $msg));

            $jobsQuery = Job::where('status', 1);
            if (!empty($cleanKeyword) && mb_strlen($cleanKeyword) >= 2) {
                $jobsQuery->where(function($q) use ($cleanKeyword) {
                    $q->where('title', 'like', '%' . $cleanKeyword . '%')
                      ->orWhere('keywords', 'like', '%' . $cleanKeyword . '%')
                      ->orWhere('description', 'like', '%' . $cleanKeyword . '%')
                      ->orWhere('company_name', 'like', '%' . $cleanKeyword . '%')
                      ->orWhere('province', 'like', '%' . $cleanKeyword . '%');
                });
            }

            $jobs = $jobsQuery->orderBy('isFeatured', 'desc')->orderBy('created_at', 'desc')->take(3)->get();

            // Nếu không tìm thấy theo từ khóa, lấy 3 việc làm nổi bật nhất gợi ý
            $isFallback = false;
            if ($jobs->isEmpty()) {
                $jobs = Job::where('status', 1)->orderBy('isFeatured', 'desc')->orderBy('created_at', 'desc')->take(3)->get();
                $isFallback = true;
            }

            if ($jobs->isNotEmpty()) {
                if ($isFallback && !empty($cleanKeyword)) {
                    $reply = "Hiện chưa có bài đăng khớp tuyệt đối với từ khóa **\"{$cleanKeyword}\"**, nhưng mình gợi ý 3 cơ hội việc làm đang HOT nhất trên hệ thống:\n\n";
                } else {
                    $reply = "Dưới đây là các vị trí việc làm phù hợp nhất trên hệ thống dành cho bạn:\n\n";
                }

                foreach ($jobs as $job) {
                    $salary = !empty($job->salary) ? $job->salary : 'Thỏa thuận';
                    $location = !empty($job->province) ? $job->province : 'Toàn quốc';
                    $reply .= "🔹 **{$job->title}**\n";
                    $reply .= "   🏢 {$job->company_name}\n";
                    $reply .= "   💰 Mức lương: {$salary} | 📍 {$location}\n";
                    $reply .= "   👉 [Xem chi tiết & Ứng tuyển ngay](/jobs/detail/{$job->id})\n\n";
                }
                $reply .= "Bạn cũng có thể xem toàn bộ việc làm tại trang [**Tìm việc làm**](/jobs)!";
                return $reply;
            }
        }

        // 2. Trường hợp: Tư vấn viết CV / sửa CV
        if (Str::contains($lower, ['cv', 'hồ sơ', 'kinh nghiệm', 'mục tiêu', 'viết cv', 'tạo cv', 'sửa cv'])) {
            return "💡 **Bí quyết viết CV nổi bật chuẩn TopCV (Không bị văn mẫu AI):**\n\n"
                . "1. **Áp dụng công thức Google XYZ:** *\"Đạt được [X], đo lường bằng con số [Y], thông qua hành động/công nghệ [Z]\"*.\n"
                . "2. **Thêm số liệu đo lường:** Thay vì ghi *\"Làm trang web\"*, hãy ghi *\"Xây dựng hệ thống Core API phục vụ 100.000 users/tháng, tối ưu tốc độ tải trang nhanh hơn 65%\"*.\n"
                . "3. **Tránh từ ngữ sáo rỗng:** Bỏ ngay các từ như *\"chăm chỉ\", \"nhiệt huyết cống hiến\", \"người năng động\"*.\n\n"
                . "👉 Bạn có thể trải nghiệm ngay công cụ **[Trình tạo CV Online](/cv-builder)** của TopWork với sẵn 3 mẫu nội dung thực chiến và xuất file PDF chuẩn A4 hoàn toàn miễn phí!";
        }

        // 3. Trường hợp: Luyện phỏng vấn / Câu hỏi phỏng vấn
        if (Str::contains($lower, ['phỏng vấn', 'interview', 'câu hỏi', 'trả lời phỏng vấn', 'deal lương', 'mức lương'])) {
            return "🎯 **5 Câu hỏi phỏng vấn phổ biến nhất & Cách trả lời khôn ngoan:**\n\n"
                . "1. **\"Hãy giới thiệu ngắn gọn về bản thân?\"**\n"
                . "   👉 *Công thức:* 60 giây tóm tắt (Số năm kinh nghiệm + Thành tích nổi bật nhất + Lý do bạn quan tâm vị trí này).\n\n"
                . "2. **\"Điểm mạnh lớn nhất của bạn là gì?\"**\n"
                . "   👉 Nêu kèm minh chứng cụ thể trong dự án gần nhất, không nói chung chung.\n\n"
                . "3. **\"Kỳ vọng mức lương của bạn là bao nhiêu?\"**\n"
                . "   👉 Nêu dải lương (ví dụ: *15 - 18 triệu Net* kèm giải thích dựa trên năng lực và giá trị bạn có thể mang lại ngay cho công ty).\n\n"
                . "4. **\"Tại sao bạn nghỉ việc ở công ty cũ?\"**\n"
                . "   👉 Hướng tới tương lai: *Tìm kiếm thử thách công nghệ mới hoặc cơ hội phát triển quy mô lớn hơn*.\n\n"
                . "Bạn đang chuẩn bị phỏng vấn cho vị trí nào? Hãy nhắn cho mình để mình đưa ra bộ câu hỏi sát nhất nhé!";
        }

        // 4. Trường hợp: Chào hỏi
        if (Str::contains($lower, ['chào', 'hello', 'hi', 'bạn là ai', 'giúp gì'])) {
            $userName = Auth::check() ? Auth::user()->fullname : 'bạn';
            return "Xin chào {$userName}! 👋 Mình là **Trợ lý AI TopWork** (được tích hợp cùng Google Gemini).\n\n"
                . "Mình có thể hỗ trợ bạn:\n"
                . "• 🔍 **Tìm việc làm phù hợp:** (Ví dụ gõ: *Tìm việc Laravel ở Hà Nội*)\n"
                . "• 📝 **Tư vấn viết CV chuẩn:** (Ví dụ gõ: *Góp ý viết CV*)\n"
                . "• 🎯 **Luyện phỏng vấn thử & Deal lương**\n\n"
                . "Hôm nay bạn cần mình hỗ trợ điều gì?";
        }

        // 5. Phản hồi chung
        return "Cảm ơn bạn đã trò chuyện! Để mình hỗ trợ bạn tốt nhất, bạn có thể chọn một trong các gợi ý dưới đây:\n\n"
            . "• Gõ: **\"Tìm việc làm [Ngành nghề / Vị trí]\"** để xem các việc làm đang mở.\n"
            . "• Gõ: **\"Cách viết CV chuẩn\"** để nhận mẹo viết CV có số liệu thực chiến.\n"
            . "• Gõ: **\"Câu hỏi phỏng vấn [Vị trí]\"** để luyện tập trả lời phỏng vấn.\n\n"
            . "Hoặc bạn có thể truy cập [**Trình tạo CV Online**](/cv-builder) để tạo ngay bản CV đẹp mắt nhé!";
    }
}
