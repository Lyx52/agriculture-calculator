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
        Schema::create('agriculture_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer');
            $table->string('model');
            $table->string('equipment_category_code');
            $table->string('equipment_sub_category_code')->nullable();
            $table->date('purchased_date');
            $table->double('price');

            // Specifications
            $table->boolean('is_self_propelled')->default(false);
            $table->string('drive_type')->nullable();
            $table->double('work_amount')->nullable();
            $table->string('work_amount_type')->nullable();
            $table->double('weight')->nullable();
            $table->double('required_power')->nullable();
            $table->double('power')->nullable();
            $table->double('working_speed')->nullable();
            $table->double('specific_fuel_consumption')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agriculture_equipment');
    }
};
