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
        if (Schema::hasTable('lecturer_classes')) {
            return;
        }

        Schema::create('lecturer_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecturer_id');
            $table->string('class_name');
            $table->timestamp('created_at')->nullable();

            $table->foreign('lecturer_id')->references('id')->on('profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturer_classes');
    }
};
