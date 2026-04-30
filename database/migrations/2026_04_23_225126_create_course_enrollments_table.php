<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('course_enrollments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('course_id')->constrained()->cascadeOnDelete();

        $table->integer('completion_percentage')->default(0);
        $table->boolean('is_completed')->default(false);

        $table->timestamp('enrolled_at')->nullable();
        $table->timestamp('completed_at')->nullable();

        $table->timestamps();

        // prevent duplicate enrollment
        $table->unique(['user_id', 'course_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrollments');
    }
};
