<?php

use App\Models\Codifier;
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
        Schema::create('codifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Codifier::class, 'parent_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->index()->unique();
            $table->json('value')->nullable()->default(null);
            $table->timestamps();
        });

        Artisan::call('db:seed', ['--class', 'CodifiersTableSeeder']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codifiers');
    }
};
