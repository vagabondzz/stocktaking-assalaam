<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stocktaking_progress_draft_items')) {
            return;
        }

        Schema::create('stocktaking_progress_draft_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stocktaking_progress_draft_id')
                ->constrained('stocktaking_progress_drafts', indexName: 'st_prog_draft_items_draft_fk')
                ->cascadeOnDelete();
            $table->string('item_identity_key');
            $table->string('item_detail_id')->nullable();
            $table->string('barang_id')->nullable();
            $table->string('kode_plu')->nullable();
            $table->string('kode_barcode')->nullable();
            $table->string('nama_barang')->nullable();
            $table->string('uom')->nullable();
            $table->string('draft_action')->default('edited');
            $table->boolean('is_counted')->default(true);
            $table->decimal('draft_qty', 18, 3)->nullable();
            $table->text('draft_note')->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['stocktaking_progress_draft_id', 'item_identity_key'],
                'stocktaking_progress_draft_items_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocktaking_progress_draft_items');
    }
};
