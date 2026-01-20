<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->text('description_ar');
            $table->text('description_en');
            $table->string('category')->nullable();
            $table->decimal('min_gpa', 4, 2)->nullable();
            $table->json('required_skills')->nullable();
            $table->json('career_prospects')->nullable();
            $table->json('universities')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->default('#3b82f6');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};
