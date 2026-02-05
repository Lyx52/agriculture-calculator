<?php

use App\Models\FarmEmployee;
use App\Models\Farmland;
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
        Schema::create('farmland_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Farmland::class, 'farmland_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(FarmEmployee::class, 'employee_id')->nullable();
            $table->string('operation_code')->nullable();
            $table->date('operation_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmland_operations');
    }
};
