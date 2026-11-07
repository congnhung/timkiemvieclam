<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
  public function markAsRead($id)
  {
    $notification = auth()->user()->unreadNotifications->where('id', $id)->first();
    if ($notification) {
      $notification->markAsRead();
      $jobId = $notification->data['job_id'];
    }
    return redirect()->route('admin.edit.job', $jobId);
  }
}
