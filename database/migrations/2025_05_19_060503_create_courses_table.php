<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text('description')->nullable();
            $table->foreignIdFor(Category::class)->constrained()->onDelete("cascade");
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete("set null");
            $table->string('duration')->nullable();
            $table->enum("difficulty_level", ["beginner", "intermediate", "advanced"]);
            $table->string("thumbnail_url");
            $table->decimal("price", 8, 2)->nullable();
            $table->string("link")->nullable();
            $table->json("documents")->nullable();
            $table->string('default_language');
            $table->boolean("is_sequential")->default(false);
            $table->boolean("accepts_mtn")->default(false);
            $table->boolean("accepts_syriatel")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
