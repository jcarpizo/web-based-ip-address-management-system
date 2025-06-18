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
        Schema::create('ip_address_app_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();            // e.g. "IP Service Key"
            $table->string('key')->unique();               // the API key
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_address_app_keys');
    }
};
