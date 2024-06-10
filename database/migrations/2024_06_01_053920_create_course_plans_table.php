<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->string('task_name', 300);
            $table->string('task_type', 100);
            $table->string('task_detail',2000);
            $table->integer('lo_amount');
            $table->float('mark');
            $table->date('starting_date');
            $table->date('finishing_date');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_plans');
    }
};
