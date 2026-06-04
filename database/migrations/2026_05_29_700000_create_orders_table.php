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
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending'); // 'pending': 未発注, 'ordered': 発注済, 'received': 納品済
            $table->integer('ordered_qty')->default(0); // 発注中数量
            $table->date('ordered_date')->nullable();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete()->nullable();
            $table->date('received_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('lot_number')->nullable();
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
