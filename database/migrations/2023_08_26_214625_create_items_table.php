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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 50);
            $table->foreignId('product_id')->unsigned();
            $table->string('item_name', 100);
            $table->double('selling_price');
            $table->double('capital_price');
            $table->foreignId('unit_id')->unsigned();
            $table->integer('stock')->default(0);
            $table->foreignId('category_id')->unsigned();
            $table->integer('withdrawn')->default(0);
            $table->foreignId('employee_id')->unsigned();
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
