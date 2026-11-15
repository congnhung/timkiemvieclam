<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewEmployerNotification extends Notification
{
  use Queueable;

  protected $employer;

  public function __construct($employer)
  {
    $this->employer = $employer;
  }

  public function via($notifiable)
  {
    return ['database'];
  }

  public function toDatabase($notifiable)
  {
    return [
      'employer_id' => $this->employer->id,
      'employer_name' => $this->employer->fullname,
      'employer_email' => $this->employer->email,
      'company_name' => $this->employer->company_name ?? $this->employer->fullname,
      'type' => 'new_employer',
      'created_at' => now()->toDateTimeString(),
    ];
  }
}
