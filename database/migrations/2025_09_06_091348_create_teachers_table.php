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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('authorisation_cohort_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('business_email')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_website')->nullable();
            $table->foreignId('territory_of_origin_id')
                ->nullable()
                ->constrained('territories')
                ->nullOnDelete();
            $table->foreignId('territory_of_residence_id')
                ->nullable()
                ->constrained('territories')
                ->nullOnDelete();
            $table->string('description')->nullable();
            $table->string('gender');
            $table->boolean('gives_video_lessons');
            $table->string('name');
            $table->string('profile_image_path')->nullable();
            $table->string('qualification_string')->nullable();
            $table->boolean('teaches_at_cvi');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
