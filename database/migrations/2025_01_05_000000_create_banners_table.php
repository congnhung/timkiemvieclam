<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('image');
                $table->string('link')->nullable();
                $table->integer('status')->default(1);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            // Thêm 3 banner quảng cáo mẫu mặc định
            DB::table('banners')->insert([
                [
                    'title' => 'Top Doanh Nghiệp Tuyển Dụng Hàng Đầu',
                    'image' => 'assets/user/images/banner-1.jpg',
                    'link' => '/jobs',
                    'status' => 1,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Cơ Hội Việc Làm IT & Công Nghệ Mới',
                    'image' => 'assets/user/images/banner-2.jpg',
                    'link' => '/jobs?career=13',
                    'status' => 1,
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Khám Phá Hàng Nghìn Việc Làm Hấp Dẫn',
                    'image' => 'assets/user/images/banner3.jpg',
                    'link' => '/jobs',
                    'status' => 1,
                    'sort_order' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
