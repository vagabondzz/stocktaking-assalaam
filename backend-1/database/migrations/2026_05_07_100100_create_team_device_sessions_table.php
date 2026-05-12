<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_device_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('team_id')->nullable()->index();
            $table->string('team_no')->index();
            $table->string('kode_team')->nullable();
            $table->string('device_id')->index();
            $table->string('device_name')->nullable();
            $table->string('platform')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip_address', 64)->nullable();
            $table->string('token_identifier')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['team_no', 'device_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_device_sessions');
    }
};
