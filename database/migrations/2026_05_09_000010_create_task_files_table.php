<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_files', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('task_id')->nullable();
            $table->text('file_name');
            $table->text('file_path');
            $table->text('file_type')->nullable();
            $table->bigInteger('file_size')->default(0)->nullable();
            $table->uuid('uploaded_by')->nullable();
            $table->timestampTz('created_at')->useCurrent()->nullable();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_files');
    }
};
