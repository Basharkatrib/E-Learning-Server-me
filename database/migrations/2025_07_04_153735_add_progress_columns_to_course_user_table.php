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
        Schema::table("course_user", function (Blueprint $table) {
            $table->integer("progress")->default(0)->after("enrolled_at");
            $table->boolean("videos_completed")->default(false)->after("progress");
            $table->timestamp("completed_at")->nullable()->after("videos_completed");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("course_user", function (Blueprint $table) {
            $table->dropColumn(["progress", "videos_completed", "completed_at"]);
        });
    }
};
