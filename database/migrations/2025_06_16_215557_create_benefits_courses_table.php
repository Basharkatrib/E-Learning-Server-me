<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Course;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benefits_courses', function (Blueprint $table) {
            $table->id();
            $table->json('title'); 
            $table->foreignIdFor(Course::class)->constrained()->onDelete('cascade');
            $table->unsignedInteger('order')->default(0); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benefits_courses');
    }
};
