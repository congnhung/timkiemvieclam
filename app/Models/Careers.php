<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Careers extends Model
{
    use HasFactory;

    protected $table = 'careers';

    public function jobs() {
        return $this->hasMany(Job::class, 'career_id');
    }
}
