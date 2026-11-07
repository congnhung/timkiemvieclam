<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class NewJobNotification extends Notification
{
  use Queueable;

  protected $job;

  public function __construct($job)
  {
    $this->job = $job;
  }

  public function via($notifiable)
  {
    return ['database']; // Lưu thông báo trong cơ sở dữ liệu
  }

  public function toDatabase($notifiable)
  {
    return [
      'employer_name' => $this->job->user->fullname, // Lưu tên nhà tuyển dụng
      'job_id' => $this->job->id, // Lưu ID công việc nếu cần thiết
    ];
  }
}
