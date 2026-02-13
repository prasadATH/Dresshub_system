<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();

            // Link to inventory variant (nullable because sometimes you may import historical rows without mapping)
            $table->foreignId('product_variant_id')->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            /**
             * Snapshots for reporting (Excel columns)
             * - Dress ID
             * - name
             * - Size
             * - type
             */
            $table->string('dress_id_snapshot')->nullable();     // Excel "Dress ID"
            $table->string('name_snapshot');                     // Excel "name"
            $table->string('size_snapshot');                     // Excel "Size"
            $table->string('type_snapshot')->nullable();         // Excel "type" (Party/Casual)

            // Excel "quantity"
            $table->unsignedInteger('quantity');

            // Excel "Return dresses" (free text like "received (M, L)" / "not received" / "none")
            $table->string('return_dresses')->nullable();

            /**
             * Cost/Price snapshot at time of sale
             * Excel: unit cost, unit price
             */
            $table->decimal('unit_cost', 12, 2);      // LKR (snapshot)
            $table->decimal('unit_price', 12, 2);     // LKR (snapshot)

            /**
             * Discount snapshot
             * Excel: discount (can be 30% or value)
             */
            $table->enum('discount_type', ['none', 'percent', 'value'])->default('none');
            $table->decimal('discount_value', 12, 2)->default(0);   // percent or LKR value
            $table->decimal('discount_amount', 12, 2)->default(0);  // computed LKR discount total for this line

            /**
             * Line totals
             * Excel: total sale, total cost, sale profit (line-level)
             */
            $table->decimal('line_total_sale', 12, 2)->default(0);
            $table->decimal('line_total_cost', 12, 2)->default(0);
            $table->decimal('line_profit', 12, 2)->default(0);

            $table->timestamps();

            $table->index(['sale_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
