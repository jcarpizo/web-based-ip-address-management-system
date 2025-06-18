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
        Schema::create('ip_address_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event');               // created, updated, deleted
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->bigInteger('added_by_user_id')->nullable(true);
            $table->bigInteger('updated_by_user_id')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_address_logs');
    }
};
