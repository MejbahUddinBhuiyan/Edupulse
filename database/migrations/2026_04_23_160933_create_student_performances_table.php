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
    Schema::create('student_performances', function (Blueprint $table) {
        $table->id();

        // Relationships
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();

        // Performance metrics
        $table->decimal('average_score', 5, 2)->default(0);
        $table->integer('total_quizzes_attempted')->default(0);
        $table->integer('total_topics_analyzed')->default(0);

        // Weak / strong tracking
        $table->integer('weak_topics_count')->default(0);
        $table->integer('strong_topics_count')->default(0);

        // AI decisions
        $table->enum('recommended_difficulty', ['easy', 'medium', 'hard'])->default('easy');
        $table->integer('study_minutes_per_day')->default(30);

        // Metadata
        $table->timestamp('last_analyzed_at')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_performances');
    }
};
