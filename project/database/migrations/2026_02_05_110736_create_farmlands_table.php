<?php

use App\Models\Farm;
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
        Schema::create('farmlands', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Farm::class, 'farm_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->double('area');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmlands');
    }
};
