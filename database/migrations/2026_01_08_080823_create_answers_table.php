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
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('question_id');
            $table->uuid('participant_id');
            $table->text('content');
            $table->boolean('is_flagged')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
            $table->index(['question_id', 'submitted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('answers');
        Schema::enableForeignKeyConstraints();
    }
};
