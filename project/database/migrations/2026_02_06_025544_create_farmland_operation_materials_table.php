<?php

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
        Schema::create('farmland_operation_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FarmlandOperation::class, 'operation_id')->constrained()->cascadeOnDelete();
            $table->string('material_type');
            $table->integer('material_id');
            $table->string('material_amount_type');
            $table->double('material_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmland_operation_materials');
    }
};
