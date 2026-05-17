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
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('type', ['adoption_approved', 'adoption_rejected', 'adoption_reviewing', 'new_adoption', 'general'])
                ->default('general')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('type', ['adoption_approved', 'adoption_rejected', 'adoption_reviewing', 'general'])
                ->default('general')
                ->change();
        });
    }
};
