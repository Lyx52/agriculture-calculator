<?php

use App\Models\FarmAgricultureEquipment;
use App\Models\FarmlandOperation;
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
        Schema::create('farmland_operation_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FarmlandOperation::class, 'operation_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(FarmAgricultureEquipment::class, 'equipment_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(FarmAgricultureEquipment::class, 'attachment_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmland_operation_equipment');
    }
};
