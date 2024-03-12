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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('dosage_strength')->nullable();
            $table->integer('pres_quantity')->nullable();
            $table->integer('quantity_took')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('signa');
            $table->string('allergy')->nullable();
            $table->string('time')->nullable();
            $table->string('per_day')->nullable();
            $table->string('refill_check')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
