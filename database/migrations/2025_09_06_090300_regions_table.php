<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('english_name');
            $table->foreignId('continent_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });

        $regions = require database_path('initial_data/regions.php');

        DB::table('regions')->insert($regions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
