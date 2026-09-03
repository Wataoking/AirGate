<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('wifi_ssid')->nullable();
            $table->text('wifi_password')->nullable();
            $table->unsignedInteger('bandwidth_limit')->default(0);
            $table->string('radius_url')->nullable();
            $table->text('radius_password')->nullable();
            $table->text('radius_secret')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};