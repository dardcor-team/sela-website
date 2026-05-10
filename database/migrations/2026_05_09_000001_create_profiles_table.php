<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username')->unique()->nullable();
            $table->text('full_name')->nullable();
            $table->text('avatar_url')->nullable();
            $table->text('class_name')->nullable();
            $table->text('role')->default('student')->nullable();
            $table->timestampTz('last_login_at')->nullable();
            $table->timestampTz('updated_at')->useCurrent()->nullable();
            
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
