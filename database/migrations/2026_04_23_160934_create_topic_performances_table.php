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
    Schema::create('topic_performances', function (Blueprint $table) {
        $table->id();

        // Relationships
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('course_id')->constrained()->cascadeOnDelete();
        $table->foreignId('last_attempt_id')->nullable()->constrained('quiz_attempts')->nullOnDelete();

        // Topic tracking
        $table->string('topic');

        // Performance data
        $table->decimal('average_score', 5, 2)->default(0);
        $table->integer('attempt_count')->default(0);
        $table->integer('correct_answers')->default(0);
        $table->integer('wrong_answers')->default(0);
        $table->decimal('success_rate', 5, 2)->default(0);

        // AI rules
        $table->boolean('weakness_flag')->default(false);
        $table->enum('recommended_difficulty', ['easy', 'medium', 'hard'])->default('easy');

        $table->timestamps();

        $table->unique(['user_id', 'course_id', 'topic']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_performances');
    }
};
