<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_status_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();

            $table->enum('status', [
                'draft',
                'to_be_dispatched',
                'dispatched',
                'delivered',
                'completed',
                'cancelled',
                'returned',
                'reconciled'
            ])->index();

            // Store checklist ticks / notes per transition
            $table->json('checklist')->nullable();
            $table->text('notes')->nullable();

            // who performed it (optional for now; link later to users)
            $table->unsignedBigInteger('changed_by')->nullable();

            $table->timestamp('changed_at')->useCurrent();

            $table->index(['sale_id', 'changed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_status_histories');
    }
};
