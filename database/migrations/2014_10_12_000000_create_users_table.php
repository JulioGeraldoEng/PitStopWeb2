<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('telefone')->nullable();  // ← TELEFONE
            $table->string('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false); // ← ADMIN
            $table->rememberToken();
            $table->string('created_at')->nullable();
            $table->string('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};