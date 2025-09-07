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
            $table->string('authorisation_cohort_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('business_email');
            $table->string('business_phone');
            $table->string('business_website');
            $table->foreignId('country_of_origin_id')
                ->nullable()
                ->constrained('countries')
                ->nullOnDelete();
            $table->foreignId('country_of_residence_id')
                ->nullable()
                ->constrained('countries')
                ->nullOnDelete();
            $table->string('description');
            $table->string('gender');
            $table->string('gives_video_lessons');
            $table->string('profile_image_path');
            $table->string('qualification_string');
            $table->string('teaches_at_cvi');
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
