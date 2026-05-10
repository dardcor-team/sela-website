<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('group_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->text('role')->default('member')->nullable();
            $table->timestampTz('joined_at')->useCurrent()->nullable();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->unique(['group_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
