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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->text('full_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('password');
            $table->string('user_type')->nullable();
            $table->string('office')->nullable();
            $table->string('department_section')->nullable();
            $table->integer('active_status')->nullable();
            $table->text('last_login_time')->nullable();
            $table->integer('otp')->nullable();
            $table->date('user_registerd_date')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
