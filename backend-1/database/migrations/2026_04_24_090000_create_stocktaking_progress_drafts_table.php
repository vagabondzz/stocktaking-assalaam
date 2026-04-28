<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocktaking_progress_drafts', function (Blueprint $table) {
            $table->id();
            $table->date('draft_date')->index();
            $table->string('kode_lokasi')->index();
            $table->string('team_id')->nullable()->index();
            $table->string('team_no')->nullable();
            $table->string('kode_team')->nullable();
            $table->string('penghitung_1')->nullable();
            $table->string('penghitung_2')->nullable();
            $table->unsignedInteger('total_items')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            $table->unique(['draft_date', 'kode_lokasi'], 'stocktaking_progress_drafts_daily_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocktaking_progress_drafts');
    }
};
