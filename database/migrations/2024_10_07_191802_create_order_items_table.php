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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Foreign key to 'orders' table
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('name')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamps();
            
        });

        // Add this check
        if (Schema::hasTable('items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            });
        }
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
