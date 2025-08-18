<?php

use App\Models\Quiz;
use App\Models\User;
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
        Schema::create("quiz_attempts", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete("cascade");
            $table->foreignIdFor(Quiz::class)->constrained()->onDelete("cascade");
            $table->timestamp("started_at")->nullable();
            $table->timestamp("completed_at")->nullable();
            $table->integer("score")->nullable();
            $table->enum("status", ["in_progress", "completed", "abandoned"])->default("in_progress");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("quiz_attempts");
    }
};
