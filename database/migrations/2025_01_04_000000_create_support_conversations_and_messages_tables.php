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
        if (!Schema::hasTable('support_conversations')) {
            Schema::create('support_conversations', function (Blueprint $table) {
                $table->id();
                $table->string('session_id', 64)->unique();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name')->default('Khách truy cập');
                $table->string('user_email')->nullable();
                $table->enum('status', ['ai_answering', 'human_takeover', 'resolved'])->default('ai_answering');
                $table->integer('unread_for_admin')->default(0);
                $table->integer('unread_for_user')->default(0);
                $table->timestamp('last_message_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index('session_id');
                $table->index('status');
                $table->index('last_message_at');
            });
        }

        if (!Schema::hasTable('support_messages')) {
            Schema::create('support_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('support_conversation_id');
                $table->enum('sender_type', ['user', 'ai', 'admin']);
                $table->unsignedBigInteger('sender_id')->nullable();
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();

                $table->foreign('support_conversation_id')
                      ->references('id')
                      ->on('support_conversations')
                      ->onDelete('cascade');

                $table->index(['support_conversation_id', 'created_at']);
                $table->index('sender_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_conversations');
    }
};

