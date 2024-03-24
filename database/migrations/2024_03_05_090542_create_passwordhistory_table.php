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
        Schema::create('passwordhistory', function (Blueprint $table) {
            $table->id();
            $table->integer('added_by');
            $table->integer('user_id')->nullable();
            $table->text('password_old')->nullable();
            $table->text('password_new')->nullable();
            $table->text('password_newly_old')->nullable();
            $table->date('password_old_date')->nullable();
            $table->date('password_new_date')->nullable();
            $table->text('user_type')->nullable();
            $table->date('password_newly_old_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passwordhistory');
    }
};
