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
        Schema::table('users', function (Blueprint $table) {
            // Only add columns that don't exist yet
            if (!Schema::hasColumn('users', 'campaign')) {
                $table->string('campaign')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'site')) {
                $table->string('site')->nullable()->after('campaign');
            }
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('site');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('username');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['campaign', 'site', 'username', 'phone']);
        });
    }
};
