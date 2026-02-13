<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            $table->string('batch')->nullable();      // Batch code if needed
            $table->string('size');                   // S, M, L, XL

            $table->string('box_id');                 // required
            $table->string('zone');                   // required

            $table->unsignedInteger('quantity_on_hand')->default(0);
            $table->unsignedInteger('quantity_reserved')->default(0);

            // Costs and prices (defaults for selling; sales snapshot separately)
            $table->decimal('unit_cost', 12, 2);               // LKR
            $table->decimal('default_unit_price', 12, 2);      // LKR

            $table->unsignedInteger('low_stock_threshold')->default(1);

            $table->enum('status', ['active', 'archived'])->default('active');

            // Prevent duplicates for same product+size+batch+location
            $table->unique(['product_id', 'size', 'batch', 'box_id', 'zone'], 'uq_variant_identity');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
