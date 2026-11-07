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
        if (Schema::hasTable('job_applications')) {
            try {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE job_applications MODIFY cv_path VARCHAR(255) NULL');
            } catch (\Throwable $e) {}

            Schema::table('job_applications', function (Blueprint $table) {
                if (!Schema::hasColumn('job_applications', 'resume_id')) {
                    $table->unsignedBigInteger('resume_id')->nullable()->after('cv_path');
                }
                if (!Schema::hasColumn('job_applications', 'cv_type')) {
                    $table->string('cv_type', 20)->default('upload')->after('resume_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('job_applications')) {
            Schema::table('job_applications', function (Blueprint $table) {
                if (Schema::hasColumn('job_applications', 'cv_type')) {
                    $table->dropColumn('cv_type');
                }
                if (Schema::hasColumn('job_applications', 'resume_id')) {
                    $table->dropColumn('resume_id');
                }
            });
        }
    }
};
