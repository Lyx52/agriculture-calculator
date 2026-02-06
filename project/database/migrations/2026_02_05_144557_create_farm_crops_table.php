<?php

use App\Models\Farm;
use App\Models\FarmCrop;
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
        Schema::create('farm_crops', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'owner_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('crop_species_code');
            $table->double('costs')->default(0);
            $table->string('cost_type');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('farmlands', function (Blueprint $table) {
            $table->foreignIdFor(FarmCrop::class, 'crop_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmlands', function (Blueprint $table) {
            $table->dropColumn('crop_id');
        });

        Schema::dropIfExists('farm_crops');
    }
};
