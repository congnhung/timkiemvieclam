<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationEmployer extends Model
{
    use HasFactory;
    protected $table = 'notifications_employer';

    protected $fillable = [
        'employer_id',
        'job_notification_id',
        'user_id',
        'type',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Lấy thông tin công việc
    public function jobs()
    {
        return $this->belongsTo(Job::class, 'job_notification_id');
    }
}
