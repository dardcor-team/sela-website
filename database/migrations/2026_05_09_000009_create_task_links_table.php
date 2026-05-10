<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_links', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('task_id')->nullable();
            $table->text('url');
            $table->text('label')->nullable();
            $table->timestampTz('created_at')->useCurrent()->nullable();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_links');
    }
};
