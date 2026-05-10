<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('name');
            $table->text('course_name')->nullable();
            $table->text('class_name')->nullable();
            $table->integer('group_number')->nullable();
            $table->integer('member_limit')->default(4)->nullable();
            $table->text('invitation_code')->unique()->nullable();
            $table->text('lecture_code')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestampTz('created_at')->useCurrent()->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE "groups" ADD CONSTRAINT groups_group_number_check CHECK (group_number > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
