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
        Schema::table('farm_fertilizers', function (Blueprint $table) {
            $table->text('company')->after('name')->nullable();
            $table->string('sync_hash')->nullable();
        });

        Schema::create('user_default_models', function (Blueprint $table) {
            $table->id();
            $table->json('model');
            $table->string('model_type');
            $table->string('sync_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farm_fertilizers', function (Blueprint $table) {
            $table->dropColumn('company');
            $table->dropColumn('sync_hash');
        });

        Schema::dropIfExists('user_default_models');
    }
};
