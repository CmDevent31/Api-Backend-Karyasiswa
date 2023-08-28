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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['Admin', 'User'])->default('Admin');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('username')->unique();
            $table->string('bagian');
            $table->date('dob');
            $table->text('bio');
            $table->string('phone_number');
            $table->rememberToken();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
