<?php

use App\Models\User;
use App\Models\UserDashboardLayout;
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
        Schema::create('user_dashboard_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('name');
            $table->json('layout')->default('{}');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignIdFor(UserDashboardLayout::class, 'selected_layout_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_dashboard_layouts');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor(UserDashboardLayout::class, 'selected_layout_id');
        });
    }
};
