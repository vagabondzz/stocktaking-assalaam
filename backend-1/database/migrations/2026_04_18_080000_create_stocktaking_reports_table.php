<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocktaking_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->index();
            $table->dateTime('report_datetime')->nullable();
            $table->string('kode_lokasi')->index();
            $table->string('item_id')->nullable();
            $table->unsignedInteger('report_year')->nullable();
            $table->string('team_id')->nullable();
            $table->string('team_no')->nullable();
            $table->string('kode_team')->nullable();
            $table->string('penghitung_1')->nullable();
            $table->string('penghitung_2')->nullable();
            $table->unsignedInteger('total_items')->default(0);
            $table->unsignedInteger('total_logs')->default(0);
            $table->dateTime('source_updated_at')->nullable();
            $table->json('report_payload');
            $table->timestamps();

            $table->unique(['report_date', 'kode_lokasi', 'team_id'], 'stocktaking_reports_unique_daily_location_team');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocktaking_reports');
    }
};
