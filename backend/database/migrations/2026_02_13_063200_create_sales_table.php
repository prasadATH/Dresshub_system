<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // Excel "Date"
            $table->date('sale_date')->index();

            // Excel "Order ID" (can be blank/null in your sheet)
            $table->string('order_id')->nullable()->index();

            // Customer field (you sometimes store name + phone together; we split properly)
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('town')->nullable();
            $table->string('district')->nullable();

            // If you later want real_time/outstation:
            $table->enum('sale_channel', ['real_time', 'outstation'])->nullable();

            // Excel "status"
            $table->enum('status', [
                'draft',
                'to_be_dispatched',
                'dispatched',
                'delivered',
                'completed',
                'cancelled',
                'returned',
                'reconciled'
            ])->default('draft')->index();

            // Excel "delivery cost"
            $table->decimal('delivery_cost', 12, 2)->default(0);

            // Denormalized totals (fast reporting). Still computed from items at save-time.
            $table->decimal('total_sale', 12, 2)->default(0);   // Excel "total sale"
            $table->decimal('total_cost', 12, 2)->default(0);   // Excel "total cost"
            $table->decimal('sale_profit', 12, 2)->default(0);  // Excel "sale profit"

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
