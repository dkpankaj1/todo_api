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
        Schema::create('todo_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('is_complete')->default(false);
            $table->foreignId('parent_id')->nullable()
                ->constrained('todo_tasks')
                ->restrictOnDelete();
            $table->foreignId('user_id');
            $table->integer('ordered')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todo_tasks');
    }
};
