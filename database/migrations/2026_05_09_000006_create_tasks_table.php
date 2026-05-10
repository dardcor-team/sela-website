<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('title');
            $table->text('description')->nullable();
            $table->text('category')->nullable();
            $table->text('subject')->nullable();
            $table->timestampTz('start_date')->useCurrent()->nullable();
            $table->timestampTz('due_date')->nullable();
            $table->boolean('is_group')->default(false)->nullable();
            $table->uuid('group_id')->nullable();
            $table->uuid('created_by')->nullable();
            $table->text('status')->default('To Do')->nullable();
            $table->text('priority')->default('Medium')->nullable();
            $table->text('link')->nullable();
            $table->text('file_path')->nullable();
            $table->timestampTz('created_at')->useCurrent()->nullable();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
