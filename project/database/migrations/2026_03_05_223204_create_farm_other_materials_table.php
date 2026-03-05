<?php

use App\Enums\OtherMaterialType;
use App\Enums\UnitType;
use App\Models\User;
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
        Schema::create('farm_other_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'owner_id')->constrained()->cascadeOnDelete();
            $table->double('cost_per_unit')->default(0);
            $table->string('unit_type')->default(UnitType::KILOGRAMS->value);
            $table->string('other_material_type')->default(OtherMaterialType::WATER->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_other_materials');
    }
};
