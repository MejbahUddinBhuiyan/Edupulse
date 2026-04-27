<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_attempt_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('selected_option_id')
                ->nullable()
                ->constrained('question_options')
                ->nullOnDelete();

            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('marks_awarded', 8, 2)->default(0);
            $table->text('teacher_feedback')->nullable();

            $table->timestamps();

            $table->unique(['quiz_attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};