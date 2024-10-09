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
            $table->foreignId('owner_id')->constrained('users'); // Foreign key referencing 'users' table
            $table->string('user_name');
            $table->string('user_email');

            // Shipping Details
            $table->string('shipping_name');
            $table->string('shipping_email');
            $table->string('shipping_phone');
            $table->string('shipping_city');
            $table->string('shipping_upzila');
            $table->text('shipping_address');
            $table->string('shipping_zip');

            // Status
            $table->enum('status', ['Pending', 'Approve', 'Processing', 'Courier', 'Shipped', 'Delivered'])
                ->default('Pending');

            // Order Note and Financial Information
            $table->text('order_note')->nullable();
            $table->decimal('sub_total', 10, 2)->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->decimal('bill', 10, 2)->nullable();

            // Payment Info
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('account_no')->nullable();
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
