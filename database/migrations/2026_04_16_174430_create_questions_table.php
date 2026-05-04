<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('question_text');
            $table->enum('question_type', ['mcq', 'text'])->default('mcq');
            $table->string('topic');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->decimal('marks', 8, 2)->default(1);
            $table->text('correct_answer')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};