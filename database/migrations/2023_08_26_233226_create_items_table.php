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
            $table->unsignedBigInteger('product_id');
            $table->string('item_name', 100);
            $table->double('selling_price');
            $table->double('capital_price');
            $table->unsignedBigInteger('unit_id');
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->integer('withdrawn')->default(0);
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('supplier_id')->references('id')->on('suppliers');

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
