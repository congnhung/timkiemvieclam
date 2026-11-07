<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
  use HasFactory;

  protected $table = 'jobs';

  public function jobType()
  {
    return $this->belongsTo(JobType::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function career()
  {
    return $this->belongsTo(Careers::class);
  }

  public function applications()
  {
    return $this->hasMany(JobApplication::class);
  }
}
