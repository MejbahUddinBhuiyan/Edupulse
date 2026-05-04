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
    Schema::create('recommendations', function (Blueprint $table) {
        $table->id();

        // Relationships
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();

        // Context
        $table->string('topic')->nullable();

        // Recommendation details
        $table->enum('type', [
            'weak_topic',
            'difficulty_upgrade',
            'study_plan',
            'content_recommendation',
        ]);

        $table->string('title');
        $table->text('message');

        // AI output
        $table->enum('recommended_difficulty', ['easy', 'medium', 'hard'])->nullable();

        // Flexible content linking
        $table->enum('content_type', ['course', 'quiz', 'lesson'])->nullable();
        $table->unsignedBigInteger('content_id')->nullable();

        // Meta
        $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
        $table->boolean('is_read')->default(false);
        $table->string('generated_from')->default('topic_rule');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
