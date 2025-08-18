<?php

use App\Models\Quiz;
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
        Schema::create("questions", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Quiz::class)->constrained()->onDelete("cascade");
            $table->text("question_text");
            $table->enum("question_type", ["true_false", "multiple_choice"]);
            $table->integer("points")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("questions");
    }
};
