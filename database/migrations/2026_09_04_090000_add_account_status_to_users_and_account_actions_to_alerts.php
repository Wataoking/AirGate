<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_status')->default('approved')->after('role');
        });

        Schema::table('alerts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('modem_id')->constrained()->nullOnDelete();
            $table->string('kind')->default('system')->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'kind']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('account_status');
        });
    }
};
