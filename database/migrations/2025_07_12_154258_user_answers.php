<?php

use App\Models\Option;
use App\Models\Question;
use App\Models\QuizAttempt;
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
        Schema::create("user_answers", function (Blueprint $table){
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete("cascade");
            $table->foreignIdFor(QuizAttempt::class)->constrained()->onDelete("cascade");
            $table->foreignIdFor(Question::class)->constrained()->onDelete("cascade");
            $table->foreignIdFor(Option::class)->nullable()->constrained()->onDelete("cascade");
            $table->boolean("is_correct")->default(false);
            $table->integer("points_earned")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_answers");
    }
};
