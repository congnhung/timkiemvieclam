<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApply extends Model
{
  protected $table = 'job_applications';
  use HasFactory;
  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function job()
  {
    return $this->belongsTo(Job::class);
  }
  public function employer()
  {
    return $this->belongsTo(User::class, 'employer_id', 'id');
  }
}
