<?php

namespace App\Http\Controllers\user;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Mở hoặc khởi tạo cuộc hội thoại từ trang chi tiết việc làm
     */
    public function initConversation(Request $request)
    {
        $jobId = $request->input('job_id');
        $job = Job::with('user')->find($jobId);

        if (!$job) {
            return response()->json([
                'status' => false,
                'message' => 'Công việc không tồn tại.'
            ], 404);
        }

        $employerId = $job->user_id;
        $candidateId = $request->input('candidate_id') ?? $request->input('user_id');

        if (Auth::id() == $employerId) {
            if (!$candidateId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vui lòng chọn ứng viên để nhắn tin.'
                ], 400);
            }
            $userId = $candidateId;
            $otherUser = User::find($candidateId);
            if (!$otherUser) {
                return response()->json(['status' => false, 'message' => 'Ứng viên không tồn tại.'], 404);
            }
        } else {
            $userId = Auth::id();
            $otherUser = $job->user;
        }

        if ($userId == $employerId) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không thể tự nhắn tin với chính mình.'
            ], 400);
        }

        // Tìm hoặc tạo cuộc hội thoại
        $conversation = Conversation::firstOrCreate(
            [
                'job_id' => $job->id,
                'user_id' => $userId,
                'employer_id' => $employerId,
            ],
            [
                'last_message_at' => now(),
            ]
        );

        return response()->json([
            'status' => true,
            'conversation_id' => $conversation->id,
            'job' => [
                'id' => $job->id,
                'title' => $job->title,
                'company_name' => $job->company_name ?? ($job->user->fullname ?? 'Nhà tuyển dụng'),
            ],
            'other_user' => [
                'id' => $otherUser->id,
                'name' => $otherUser->fullname ?? 'Nhà tuyển dụng',
                'avatar' => !empty($otherUser->image)
                    ? asset('assets/user/profile_picture/thumb/' . $otherUser->image)
                    : asset('assets/user/images/avatar7.png'),
            ]
        ]);
    }

    /**
     * Lấy danh sách tin nhắn của 1 cuộc hội thoại
     */
    public function getMessages($conversationId)
    {
        $conversation = Conversation::with(['job', 'user', 'employer'])->find($conversationId);

        if (!$conversation) {
            return response()->json(['status' => false, 'message' => 'Hội thoại không tồn tại.'], 404);
        }

        if ($conversation->user_id != Auth::id() && $conversation->employer_id != Auth::id()) {
            return response()->json(['status' => false, 'message' => 'Bạn không có quyền xem cuộc trò chuyện này.'], 403);
        }

        // Đánh dấu đã đọc các tin nhắn gửi cho tôi
        Message::where('conversation_id', $conversationId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where('conversation_id', $conversationId)
            ->with(['sender'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->fullname ?? 'Người dùng',
                    'sender_avatar' => !empty($msg->sender->image)
                        ? asset('assets/user/profile_picture/thumb/' . $msg->sender->image)
                        : asset('assets/user/images/avatar7.png'),
                    'message' => $msg->message,
                    'is_me' => $msg->sender_id == Auth::id(),
                    'created_at' => $msg->created_at->format('H:i - d/m/Y'),
                    'created_at_human' => $msg->created_at->diffForHumans(),
                ];
            });

        $other = $conversation->getOtherParticipant(Auth::id());

        return response()->json([
            'status' => true,
            'conversation' => [
                'id' => $conversation->id,
                'job' => $conversation->job ? [
                    'id' => $conversation->job->id,
                    'title' => $conversation->job->title,
                ] : null,
                'other_user' => [
                    'id' => $other->id ?? 0,
                    'name' => $other->fullname ?? 'Người dùng',
                    'avatar' => !empty($other->image)
                        ? asset('assets/user/profile_picture/thumb/' . $other->image)
                        : asset('assets/user/images/avatar7.png'),
                ]
            ],
            'messages' => $messages
        ]);
    }

    /**
     * Gửi tin nhắn mới
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:3000',
        ], [
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.max' => 'Tin nhắn quá dài (tối đa 3000 ký tự).'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $conversation = Conversation::find($conversationId);
        if (!$conversation) {
            return response()->json(['status' => false, 'message' => 'Hội thoại không tồn tại.'], 404);
        }

        if ($conversation->user_id != Auth::id() && $conversation->employer_id != Auth::id()) {
            return response()->json(['status' => false, 'message' => 'Không có quyền gửi tin nhắn.'], 403);
        }

        $receiverId = ($conversation->user_id == Auth::id()) 
            ? $conversation->employer_id 
            : $conversation->user_id;

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Phát sóng realtime qua Pusher nếu được bật
        try {
            if (config('broadcasting.default') === 'pusher' && !empty(config('broadcasting.connections.pusher.key'))) {
                broadcast(new MessageSent($message))->toOthers();
            }
        } catch (\Throwable $e) {
            Log::warning('Pusher Broadcast Error: ' . $e->getMessage());
        }

        $user = Auth::user();

        return response()->json([
            'status' => true,
            'message' => [
                'id' => $message->id,
                'conversation_id' => $message->conversation_id,
                'sender_id' => $message->sender_id,
                'sender_name' => $user->fullname ?? 'Tôi',
                'sender_avatar' => !empty($user->image)
                    ? asset('assets/user/profile_picture/thumb/' . $user->image)
                    : asset('assets/user/images/avatar7.png'),
                'message' => $message->message,
                'is_me' => true,
                'created_at' => $message->created_at->format('H:i - d/m/Y'),
                'created_at_human' => $message->created_at->diffForHumans(),
            ]
        ]);
    }

    /**
     * Màn hình danh sách tin nhắn toàn diện (/account/messages)
     */
    public function index(Request $request)
    {
        $currentUserId = Auth::id();

        $conversations = Conversation::where('user_id', $currentUserId)
            ->orWhere('employer_id', $currentUserId)
            ->with(['job', 'user', 'employer', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conv) use ($currentUserId) {
                $other = $conv->getOtherParticipant($currentUserId);
                $unread = Message::where('conversation_id', $conv->id)
                    ->where('receiver_id', $currentUserId)
                    ->where('is_read', false)
                    ->count();

                $conv->other_user = $other;
                $conv->unread_count = $unread;
                return $conv;
            });

        $activeConversationId = $request->query('conversation_id', $conversations->first()->id ?? null);
        $activeConversation = null;
        $activeMessages = collect();

        if ($activeConversationId) {
            $activeConversation = $conversations->firstWhere('id', $activeConversationId);
            if ($activeConversation) {
                // Đánh dấu đã đọc
                Message::where('conversation_id', $activeConversationId)
                    ->where('receiver_id', $currentUserId)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

                $activeConversation->unread_count = 0;

                $activeMessages = Message::where('conversation_id', $activeConversationId)
                    ->with('sender')
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('front.account.messages', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'activeMessages' => $activeMessages,
        ]);
    }

    /**
     * Đếm tổng số tin nhắn chưa đọc của người dùng hiện tại (cho badge trên Header)
     */
    public function unreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
