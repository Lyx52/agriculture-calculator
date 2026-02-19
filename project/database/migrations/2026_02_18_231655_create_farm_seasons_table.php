<?php

use App\Models\FarmSeason;
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
        Schema::create('farm_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'owner_id')->constrained()->cascadeOnDelete();
            $table->integer('name');
        });

        Schema::table('farmland_operations', function (Blueprint $table) {
            $table->foreignIdFor(FarmSeason::class, 'season_id')->after('employee_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmland_operations', function (Blueprint $table) {
            $table->dropColumn('season_id');
        });

        Schema::dropIfExists('farm_seasons');
    }
};
