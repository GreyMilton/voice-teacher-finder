<?php

use App\Models\Territory;
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
        Schema::create('territories', function (Blueprint $table) {
            $table->id();
            $table->string('geo_point');
            $table->string('iso_3_country_code');
            $table->string('english_name');
            $table->string('continent');
            $table->string('region');
            $table->string('french_name');
            $table->timestamps();
        });

        $territories = require database_path('initial_data/territories.php');

        foreach ($territories as $territory) {
            Territory::create($territory);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('territories');
    }
};
