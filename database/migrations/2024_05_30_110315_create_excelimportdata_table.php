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
        Schema::create('excelimportdata', function (Blueprint $table) {
            $table->bigint('id');
            $table->date('invoice_date')->nullable();
            $table->text('customer_name')->nullable();
            $table->text('shippingbill_number')->nullable();
            $table->date('shippingbill_date')->nullable();
            $table->text('salesorder_number')->nullable();
            $table->date('salesorder_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excelimportdata');
    }
};
