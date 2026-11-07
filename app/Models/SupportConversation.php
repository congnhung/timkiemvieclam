<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportConversation extends Model
{
    use HasFactory;

    protected $table = 'support_conversations';

    protected $fillable = [
        'session_id',
        'user_id',
        'user_name',
        'user_email',
        'status', // 'ai_answering', 'human_takeover', 'resolved'
        'unread_for_admin',
        'unread_for_user',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class, 'support_conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(SupportMessage::class, 'support_conversation_id')->latestOfMany();
    }
}

