<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('avatar_path');
            $table->string('designation', 100)->nullable()->after('phone');
            $table->json('notification_prefs')->nullable()->after('designation');
            $table->timestamp('suspended_at')->nullable()->after('remember_token');
            $table->timestamp('last_seen_at')->nullable()->after('suspended_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'designation', 'notification_prefs', 'suspended_at', 'last_seen_at']);
        });
    }
};
