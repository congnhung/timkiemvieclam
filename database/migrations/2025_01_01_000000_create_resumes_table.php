<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('resumes')) {
            Schema::create('resumes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('title')->default('CV Chưa đặt tên');
                $table->string('fullname')->nullable();
                $table->string('candidate_title')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('birthday')->nullable();
                $table->string('gender')->nullable();
                $table->string('address')->nullable();
                $table->string('website')->nullable();
                $table->string('avatar')->nullable();
                $table->string('theme_color')->default('#00b14f');
                $table->string('template')->default('modern');
                $table->text('objective')->nullable();
                $table->longText('experience_data')->nullable();
                $table->longText('education_data')->nullable();
                $table->longText('skills_data')->nullable();
                $table->longText('projects_data')->nullable();
                $table->longText('certifications_data')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();

                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};

