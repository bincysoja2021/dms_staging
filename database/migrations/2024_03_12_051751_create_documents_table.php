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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->text('doc_id')->nullable();
            $table->text('user_name')->nullable();
            $table->date('date')->nullable();
            $table->text('document_type')->nullable();
            $table->text('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->text('sales_order_number')->nullable();
            $table->text('shipping_bill_number')->nullable();
            $table->text('company_name')->nullable();
            $table->text('company_id')->nullable();
            $table->text('filename')->nullable();
            $table->text('status')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
