<?php

use App\Models\Question;
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
        Schema::create("options", function(Blueprint $table){
            $table->id();
            $table->foreignIdFor(Question::class)->constrained()->onDelete("cascade");
            $table->text("option_text");
            $table->boolean("is_correct")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("options");
    }
};
