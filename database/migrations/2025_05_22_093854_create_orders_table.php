<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();              // ORD-20250522-0001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('user_name');
            $table->string('contact_number');

            $table->json('items_id');        // array of item IDs
            $table->json('item_details');    // key-value pair with item info

            $table->decimal('total_price', 10, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();

            $table->json('address');         // delivery address
            $table->boolean('is_gift')->default(false);

            $table->string('payment_mode');  // cash, card, upi, etc.
            $table->string('payment_id')->nullable();
            $table->string('payment_status')->default('pending'); // paid, failed, refunded

            $table->string('order_status')->default('pending');   // pending, confirmed, shipped, delivered, cancelled
            $table->dateTime('delivery_date')->nullable();

            $table->text('notes')->nullable();
            $table->json('transaction_log')->nullable(); // optional for payment gateway logs
            $table->string('referrer')->nullable();      // for marketing

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};