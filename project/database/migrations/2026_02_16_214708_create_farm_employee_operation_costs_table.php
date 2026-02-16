<?php

use App\Models\Codifier;
use App\Models\FarmEmployee;
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
        Schema::create('farm_employee_operation_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FarmEmployee::class, 'employee_id');
            $table->string('operation_type_code');
            $table->double('costs')->default(0);
            $table->string('cost_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_employee_operation_costs');
    }
};
