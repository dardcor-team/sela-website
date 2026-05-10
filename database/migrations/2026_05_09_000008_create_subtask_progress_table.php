<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subtask_progress', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('subtask_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->integer('progress')->default(0)->nullable();
            $table->timestampTz('updated_at')->useCurrent()->nullable();

            $table->foreign('subtask_id')->references('id')->on('subtasks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->unique(['subtask_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtask_progress');
    }
};
