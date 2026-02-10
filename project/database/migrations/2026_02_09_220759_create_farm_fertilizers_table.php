<?php

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
        Schema::create('farm_fertilizers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'owner_id')->constrained()->cascadeOnDelete();
            $table->string("name")->nullable();
            $table->string("company")->nullable();
            $table->double('cost_per_unit')->default(0);
            $table->string('unit_type')->default(UnitType::KILOGRAMS->value);
            $table->double('value_n')->default(0);
            $table->double('value_p2o5')->default(0);
            $table->double('value_k2o')->default(0);
            $table->double('value_ca')->default(0);
            $table->double('value_mg')->default(0);
            $table->double('value_na')->default(0);
            $table->double('value_s')->default(0);
            $table->double('value_b')->default(0);
            $table->double('value_co')->default(0);
            $table->double('value_cu')->default(0);
            $table->double('value_fe')->default(0);
            $table->double('value_mn')->default(0);
            $table->double('value_mo')->default(0);
            $table->double('value_zn')->default(0);
            $table->double('value_caco3')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_fertilizers');
    }
};
