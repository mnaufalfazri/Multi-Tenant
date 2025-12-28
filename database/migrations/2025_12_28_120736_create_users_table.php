<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT

            $table->string('name', 120);
            $table->string('email', 190)->unique();

            // Laravel expects this column name by default
            $table->string('password'); // 255

            $table->timestamp('email_verified_at')->nullable();

            // Optional but common (useful if later you add session-based auth)
            $table->rememberToken();

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
