<?php

use App\Models\Quiz;
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
        Schema::create("certificates", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete("cascade");
            $table->foreignIdFor(Quiz::class)->constrained()->onDelete("cascade");
            $table->foreignIdFor(QuizAttempt::class)->constrained()->onDelete("cascade");
            $table->string("certificate_number")->unique();
            $table->string("file_path")->nullable();
            $table->date("issue_date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
