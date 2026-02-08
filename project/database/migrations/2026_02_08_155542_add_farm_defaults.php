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
        Schema::table('farms', function (Blueprint $table) {
            $table->double('fuel_cost')->default(0.8);
            $table->double('default_employee_salary')->default(15);
            $table->string('default_employee_salary_type')->default('eur_hour');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            $table->dropColumn('fuel_cost');
            $table->dropColumn('default_employee_salary');
            $table->dropColumn('default_employee_salary_type');
        });
    }
};
