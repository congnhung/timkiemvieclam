<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $table = 'resumes';

    protected $fillable = [
        'user_id',
        'title',
        'fullname',
        'candidate_title',
        'email',
        'phone',
        'birthday',
        'gender',
        'address',
        'website',
        'avatar',
        'theme_color',
        'template',
        'objective',
        'experience_data',
        'education_data',
        'skills_data',
        'projects_data',
        'certifications_data',
        'is_default',
    ];

    protected $casts = [
        'experience_data' => 'array',
        'education_data' => 'array',
        'skills_data' => 'array',
        'projects_data' => 'array',
        'certifications_data' => 'array',
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

