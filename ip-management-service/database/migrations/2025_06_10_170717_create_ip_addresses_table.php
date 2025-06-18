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
        Schema::create('ip_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label')->nullable(false);
            $table->string('ip_address')->comment('ipv4 or ipv6')->nullable(false)->unique();
            $table->string('comments')->comment('notes or comments')->nullable();
            $table->bigInteger('added_by_user_id')->nullable(false);
            $table->bigInteger('updated_by_user_id')->nullable(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_addresses');
    }
};
