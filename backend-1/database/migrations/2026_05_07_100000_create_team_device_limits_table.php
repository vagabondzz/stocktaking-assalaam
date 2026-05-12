<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_device_limits', function (Blueprint $table) {
            $table->id();
            $table->string('team_id')->nullable()->index();
            $table->string('team_no')->unique();
            $table->string('kode_team')->nullable();
            $table->unsignedInteger('max_devices')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_device_limits');
    }
};
