<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();

            $table->enum('movement_type', [
                'add',
                'sale',
                'reserve',
                'release',
                'adjust',
                'damage',
                'return',
                'transfer'
            ])->index();

            $table->integer('qty_change'); // + / -
            $table->unsignedInteger('before_on_hand')->default(0);
            $table->unsignedInteger('after_on_hand')->default(0);

            $table->string('reason')->nullable();

            // reference to sale/order
            $table->string('reference_type')->nullable(); // e.g., 'sale'
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();

            $table->index(['product_variant_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
