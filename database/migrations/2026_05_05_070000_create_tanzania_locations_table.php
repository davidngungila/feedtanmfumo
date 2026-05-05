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
        Schema::create('tanzania_locations', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('regioncode', 5);
            $table->string('district');
            $table->string('districtcode', 5);
            $table->string('ward');
            $table->string('wardcode', 5);
            $table->string('street');
            $table->text('places');
            $table->timestamps();

            // Indexes for better performance
            $table->index('regioncode');
            $table->index('districtcode');
            $table->index('wardcode');
            $table->index(['region', 'district']);
            $table->index(['district', 'ward']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanzania_locations');
    }
};
