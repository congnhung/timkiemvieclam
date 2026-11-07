<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupportChatController extends Controller
{
    /**
     * Màn hình CSKH & Giám sát AI Chat trong Admin
     */
    public function index(Request $request)
    {
        $conversations = SupportConversation::with(['latestMessage', 'user'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $activeConvId = $request->query('conversation_id', $conversations->first()->id ?? null);
        $activeConversation = null;
        $activeMessages = collect();

        if ($activeConvId) {
            $activeConversation = SupportConversation::with(['user'])->find($activeConvId);
            if ($activeConversation) {
                // Đánh dấu Admin đã đọc
                $activeConversation->update(['unread_for_admin' => 0]);
                SupportMessage::where('support_conversation_id', $activeConvId)
                    ->where('sender_type', 'user')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

                $activeMessages = SupportMessage::where('support_conversation_id', $activeConvId)
                    ->with('senderUser')
                    ->orderBy('id', 'asc')
                    ->get();
            }
        }

        return view('admin.support.index', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'activeMessages' => $activeMessages,
        ]);
    }

    /**
     * Admin/CSKH gửi phản hồi trực tiếp cho khách hàng
     */
    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:3000',
        ], [
            'message.required' => 'Vui lòng nhập nội dung phản hồi.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $conversation = SupportConversation::find($id);
        if (!$conversation) {
            return response()->json(['status' => false, 'message' => 'Hội thoại không tồn tại.'], 404);
        }

        $admin = Auth::user();

        $msg = SupportMessage::create([
            'support_conversation_id' => $conversation->id,
            'sender_type' => 'admin',
            'sender_id' => $admin->id,
            'message' => trim($request->message),
            'is_read' => true,
        ]);

        // Cập nhật thời gian nhắn và số tin nhắn chưa đọc cho khách
        $conversation->update([
            'last_message_at' => now(),
            'unread_for_user' => $conversation->unread_for_user + 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => [
                'id' => $msg->id,
                'sender_type' => 'admin',
                'sender_name' => $admin->fullname ?? 'Chuyên viên CSKH',
                'message' => $msg->message,
                'created_at' => $msg->created_at->format('H:i - d/m/Y'),
            ]
        ]);
    }

    /**
     * Bật lại chế độ Gemini AI tự động trả lời hoặc chuyển đổi trạng thái
     */
    public function toggleStatus(Request $request, $id)
    {
        $conversation = SupportConversation::find($id);
        if (!$conversation) {
            return response()->json(['status' => false, 'message' => 'Hội thoại không tồn tại.'], 404);
        }

        $newStatus = $request->input('status');
        if (!in_array($newStatus, ['ai_answering', 'human_takeover', 'resolved'])) {
            $newStatus = ($conversation->status === 'human_takeover') ? 'ai_answering' : 'human_takeover';
        }

        $conversation->update(['status' => $newStatus]);

        return response()->json([
            'status' => true,
            'new_status' => $newStatus,
            'message' => 'Cập nhật trạng thái hội thoại thành công!'
        ]);
    }

    /**
     * Lấy danh sách tin nhắn mới cho Admin (Polling)
     */
    public function getNewMessages(Request $request, $id)
    {
        $lastId = (int)$request->query('last_id', 0);
        $messages = SupportMessage::where('support_conversation_id', $id)
            ->where('id', '>', $lastId)
            ->with('senderUser')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'sender_type' => $m->sender_type,
                    'sender_name' => $m->sender_type === 'admin' 
                        ? ($m->senderUser ? $m->senderUser->fullname : 'CSKH')
                        : ($m->sender_type === 'ai' ? 'Trợ lý AI' : 'Khách'),
                    'message' => $m->message,
                    'created_at' => $m->created_at->format('H:i - d/m/Y'),
                ];
            });

        return response()->json([
            'status' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Xóa hội thoại CSKH
     */
    public function destroy($id)
    {
        $conversation = SupportConversation::find($id);
        if ($conversation) {
            $conversation->delete();
        }
        return redirect()->route('admin.support.index')->with('success', 'Đã xóa hội thoại thành công.');
    }
}

