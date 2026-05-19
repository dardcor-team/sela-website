<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('user_ethol_sessions');
    }

    public function down(): void
    {
        Schema::create('user_ethol_sessions', function ($table) {
            $table->id();
            $table->uuid('user_id');
            $table->text('ethol_token');
            $table->text('ethol_cookies');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
