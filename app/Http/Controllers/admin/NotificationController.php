<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
  public function markAsRead($id)
  {
    $notification = auth()->user()->unreadNotifications->where('id', $id)->first();
    $jobId = null;
    $employerId = null;
    if ($notification) {
      $notification->markAsRead();
      $jobId = $notification->data['job_id'] ?? null;
      $employerId = $notification->data['employer_id'] ?? null;
    }
    if ($jobId) {
      return redirect()->route('admin.edit.job', $jobId);
    }
    if ($employerId) {
      return redirect()->route('admin.edit.employer', $employerId);
    }
    return redirect()->route('admin.job');
  }
}
