<?php

use App\Enums\ItemUnit;
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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('item_category_id')->constrained()->cascadeOnDelete();
            $table->integer('proper_inventory')->nullable();
            $table->string('unit')->default(ItemUnit::Gram);
            $table->decimal('gram_per_unit', 8, 3)->default(1);
            $table->string('storage_location');
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
