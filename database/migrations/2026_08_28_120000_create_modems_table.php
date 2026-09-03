<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model')->default('AirGate Pro');
            $table->string('mac_address')->unique();
            $table->string('ip_address');
            $table->decimal('data_used', 10, 2)->default(0);
            $table->decimal('bandwidth', 10, 2)->default(0);
            $table->enum('status', ['online', 'blocked'])->default('online');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modems');
    }
};