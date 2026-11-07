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
        Schema::create('deleted_jobs', function (Blueprint $table) {
            $table->id()->unsigned(); 
            $table->string('title', 255);
            $table->bigInteger('career_id')->unsigned();
            $table->bigInteger('job_type_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('vacancy');
            $table->string('salary', 255)->nullable();
            $table->text('job_level');
            $table->mediumText('description')->nullable();
            $table->mediumText('benefits')->nullable();
            $table->mediumText('responsibility')->nullable();
            $table->mediumText('qualifications')->nullable();
            $table->text('keywords')->nullable();
            $table->string('experience', 255);
            $table->string('company_name', 255);
            $table->string('province', 255)->nullable();
            $table->string('district', 255)->nullable();
            $table->string('wards', 255)->nullable();
            $table->string('location_detail', 255)->nullable();
            $table->string('company_website', 255)->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('status')->default(1);
            $table->integer('isFeatured')->default(0);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deleted_jobs');
    }
};
