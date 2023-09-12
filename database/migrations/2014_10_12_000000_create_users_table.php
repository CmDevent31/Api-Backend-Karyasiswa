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
        $table->enum('role', ['Admin', 'User']);
        $table->text('profile_image');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('username')->unique();
        $table->enum('gender', ['Pria', 'Wanita']);
        $table->string('kelas');
        $table->date('dob');
        $table->text('bio');
        $table->string('phone_number');
        $table->rememberToken();
        $table->timestamps();
    }); 
    
    // Set default role to 'User' for existing rows
    DB::table('users')->update(['role' => 'User']);
}

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
